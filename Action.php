<?php
class Whisper_Action extends Typecho_Widget implements Widget_Interface_Do
{
    private $db;
    private $options;
    
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        $this->db = Typecho_Db::get();
        $this->options = Typecho_Widget::widget('Widget_Options');
    }
    
    /**
     * 发表微语
     */
    public function publish()
    {
        // 检查登录
        $user = Typecho_Widget::widget('Widget_User');
        if (!$user->hasLogin()) {
            $this->response->throwJson(array('success' => false, 'message' => '请先登录'));
        }
        
        $content = $this->request->get('content');
        if (empty($content)) {
            $this->response->throwJson(array('success' => false, 'message' => '内容不能为空'));
        }
        
        $images = $this->request->get('images', '');
        $osInfo = $this->getOSInfo();
        
        $data = array(
            'content' => $content,
            'images' => $images,
            'os_info' => $osInfo,
            'created_at' => time(),
            'user_id' => $user->uid
        );
        
        try {
            $insertId = $this->db->query($this->db->insert('table.whispers')->rows($data));
            $this->response->throwJson(array('success' => true, 'message' => '发表成功', 'id' => $insertId));
        } catch (Exception $e) {
            $this->response->throwJson(array('success' => false, 'message' => '发表失败: ' . $e->getMessage()));
        }
    }
    
    /**
     * 删除微语
     */
    public function delete()
    {
        $user = Typecho_Widget::widget('Widget_User');
        if (!$user->hasLogin()) {
            $this->response->throwJson(array('success' => false, 'message' => '请先登录'));
        }
        
        $id = $this->request->get('id');
        if (empty($id)) {
            $this->response->throwJson(array('success' => false, 'message' => 'ID不能为空'));
        }
        
        try {
            $this->db->query($this->db->delete('table.whispers')
                ->where('id = ?', $id)
                ->where('user_id = ?', $user->uid));
            $this->response->throwJson(array('success' => true, 'message' => '删除成功'));
        } catch (Exception $e) {
            $this->response->throwJson(array('success' => false, 'message' => '删除失败'));
        }
    }
    
    /**
     * 获取微语列表
     */
    public function getList()
    {
        $page = max(1, intval($this->request->get('page', 1)));
        $pageSize = intval(Typecho_Widget::widget('Widget_Options')->plugin('Whisper')->pageSize);
        $offset = ($page - 1) * $pageSize;
        
        $whispers = $this->db->fetchAll(
            $this->db->select()->from('table.whispers')
                ->order('created_at', Typecho_Db::SORT_DESC)
                ->limit($pageSize)
                ->offset($offset)
        );
        
        // 获取用户信息
        foreach ($whispers as &$whisper) {
            // 直接从数据库查询用户信息
            $userInfo = $this->db->fetchRow(
                $this->db->select('screenName', 'mail')->from('table.users')
                    ->where('uid = ?', $whisper['user_id'])
            );
            
            if ($userInfo) {
                $whisper['author_name'] = $userInfo['screenName'];
                $whisper['author_avatar'] = $this->getAvatar($userInfo['mail'], $whisper['user_id']);
            } else {
                // 如果找不到用户，使用默认值
                $whisper['author_name'] = '博主';
                $whisper['author_avatar'] = $this->getAvatar('default@example.com', 0);
            }
            
            $whisper['time_ago'] = $this->timeAgo($whisper['created_at']);
            $whisper['images_array'] = !empty($whisper['images']) ? explode(',', $whisper['images']) : array();
        }
        
        $this->response->throwJson(array('success' => true, 'data' => $whispers));
    }
    
    /**
     * 获取操作系统信息
     */
    private function getOSInfo()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        
        if (preg_match('/Windows NT 10/i', $userAgent)) {
            return '💻 来自Windows 12 最新版';
        } elseif (preg_match('/Windows NT 11/i', $userAgent)) {
            return '💻 来自Windows 12 最新版';
        } elseif (preg_match('/Mac OS X/i', $userAgent)) {
            return '💻 来自MacOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return '📱 来自Android 最强版';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return '📱 来自Android 最强版';
        } elseif (preg_match('/iPhone|iPad/i', $userAgent)) {
            return '📱 来自iOS';
        }
        
        return '';
    }
    
    /**
     * 获取头像
     */
    private function getAvatar($email, $userId = 0)
    {
        // 使用固定头像
        return 'https://blog.zhangmingrui.top/usr/uploads/2024/08/3953595498.png';
    }
    
    /**
     * 时间转换
     */
    private function timeAgo($timestamp)
    {
        $diff = time() - $timestamp;
        $today = strtotime(date('Y-m-d'));
        $yesterday = $today - 86400;
        $postDate = strtotime(date('Y-m-d', $timestamp));
        
        // 1分钟内
        if ($diff < 60) {
            return '刚刚';
        }
        // 1小时内
        elseif ($diff < 3600) {
            return floor($diff / 60) . '分钟前';
        }
        // 今天
        elseif ($postDate == $today) {
            return '今天 ' . date('H:i', $timestamp);
        }
        // 昨天
        elseif ($postDate == $yesterday) {
            return '昨天 ' . date('H:i', $timestamp);
        }
        // 7天内
        elseif ($diff < 604800) {
            $days = array('日', '一', '二', '三', '四', '五', '六');
            return '星期' . $days[date('w', $timestamp)] . ' ' . date('H:i', $timestamp);
        }
        // 今年内
        elseif (date('Y', $timestamp) == date('Y')) {
            return date('m月d日 H:i', $timestamp);
        }
        // 更早
        else {
            return date('Y年m月d日 H:i', $timestamp);
        }
    }
    
    public function action()
    {
        $this->on($this->request->is('do=publish'))->publish();
        $this->on($this->request->is('do=delete'))->delete();
        $this->on($this->request->is('do=list'))->getList();
        $this->on($this->request->is('do=upload'))->uploadImage();
        $this->on($this->request->is('do=like'))->likeWhisper();
    }
    
    /**
     * 点赞微语
     */
    public function likeWhisper()
    {
        try {
            header('Content-Type: application/json');
            
            $id = $this->request->get('id');
            if (empty($id)) {
                echo json_encode(array('success' => false, 'message' => 'ID不能为空'));
                exit;
            }
            
            // 检查微语是否存在
            $whisper = $this->db->fetchRow(
                $this->db->select('id', 'likes')->from('table.whispers')
                    ->where('id = ?', $id)
            );
            
            if (!$whisper) {
                echo json_encode(array('success' => false, 'message' => '微语不存在'));
                exit;
            }
            
            // 检查是否已点赞（通过 Cookie）
            $likedKey = 'whisper_liked_' . $id;
            if (isset($_COOKIE[$likedKey])) {
                echo json_encode(array('success' => false, 'message' => '您已经点赞过了', 'likes' => intval($whisper['likes'])));
                exit;
            }
            
            // 增加点赞数
            $this->db->query(
                $this->db->update('table.whispers')
                    ->rows(array('likes' => intval($whisper['likes']) + 1))
                    ->where('id = ?', $id)
            );
            
            // 获取最新点赞数
            $newLikes = intval($whisper['likes']) + 1;
            
            // 设置 Cookie（30天有效）
            setcookie($likedKey, '1', time() + 2592000, '/');
            
            echo json_encode(array(
                'success' => true,
                'message' => '点赞成功',
                'likes' => $newLikes
            ));
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(array('success' => false, 'message' => '点赞失败: ' . $e->getMessage()));
            exit;
        }
    }
    
    /**
     * 上传图片
     */
    public function uploadImage()
    {
        // 设置响应头
        header('Content-Type: application/json');
        
        // 检查登录
        $user = Typecho_Widget::widget('Widget_User');
        if (!$user->hasLogin()) {
            echo json_encode(array('success' => false, 'message' => '请先登录'));
            exit;
        }
        
        if (empty($_FILES['file'])) {
            echo json_encode(array('success' => false, 'message' => '没有上传文件'));
            exit;
        }
        
        $file = $_FILES['file'];
        
        // 检查上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(array('success' => false, 'message' => '上传失败，错误代码: ' . $file['error']));
            exit;
        }
        
        // 检查文件大小（5MB）
        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(array('success' => false, 'message' => '文件大小不能超过5MB'));
            exit;
        }
        
        // 检查文件类型
        $allowedTypes = array('image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg');
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            echo json_encode(array('success' => false, 'message' => '只支持 JPG、PNG、GIF、WebP 格式'));
            exit;
        }
        
        // 生成文件名
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = date('YmdHis') . '_' . uniqid() . '.' . $ext;
        
        // 上传目录
        $uploadDir = __TYPECHO_ROOT_DIR__ . '/usr/uploads/whisper/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo json_encode(array('success' => false, 'message' => '无法创建上传目录'));
                exit;
            }
        }
        
        $uploadPath = $uploadDir . $filename;
        
        // 移动文件
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $url = $this->options->siteUrl . 'usr/uploads/whisper/' . $filename;
            echo json_encode(array(
                array(
                    'url' => $url,
                    'name' => $filename
                )
            ));
            exit;
        } else {
            echo json_encode(array('success' => false, 'message' => '文件移动失败'));
            exit;
        }
    }
}
