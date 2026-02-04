<?php
/**
 * 微语
 * 
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<!DOCTYPE html>
<html lang="zh">
<?php $this->need('header.php'); ?>
<body class="jasmine-body" data-prismjs-copy="点击复制" data-prismjs-copy-error="按Ctrl+C复制" data-prismjs-copy-success="内容已复制！">
<div class="jasmine-container grid grid-cols-12">
<?php $this->need('component/sidebar-left.php'); ?>
    <div class="flex col-span-12 lg:col-span-8 flex-col lg:border-x-2 border-stone-100 dark:border-neutral-600 lg:pt-0 lg:px-6 pb-10 px-3">
        <?php $this->need('component/menu.php'); ?>
        <div class="flex flex-col gap-y-12">
            <div></div>
            
            <!-- 微语内容开始 -->
            <div class="whisper-page-content">
                <style>
                .whisper-page-content {
                    width: 100%;
                }
                
                .whisper-container {
                    width: 100%;
                    margin: 0;
                    padding: 0;
                }
                
                .whisper-title {
                    font-size: 24px;
                    font-weight: 600;
                    color: #333;
                    margin-bottom: 30px;
                    text-align: center;
                }
                
                .dark .whisper-title {
                    color: #e5e7eb;
                }
                
                .whisper-publish {
                    background: #f8f9fa;
                    border-radius: 16px;
                    padding: 24px;
                    margin-bottom: 30px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
                }
                
                .dark .whisper-publish {
                    background: #1f2937;
                }
                
                .whisper-textarea {
                    width: 100%;
                    min-height: 120px;
                    padding: 16px;
                    border: 1px solid #e1e4e8;
                    border-radius: 12px;
                    font-size: 15px;
                    line-height: 1.6;
                    resize: vertical;
                    box-sizing: border-box;
                    transition: border-color 0.3s;
                    background: white;
                }
                
                .dark .whisper-textarea {
                    background: #111827;
                    border-color: #374151;
                    color: #e5e7eb;
                }
                
                .whisper-textarea:focus {
                    outline: none;
                    border-color: #5b7ef5;
                }
                
                .whisper-actions {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-top: 16px;
                }
                
                .whisper-emoji-btn {
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    padding: 8px;
                    transition: transform 0.2s;
                    position: relative;
                }
                
                .whisper-emoji-btn:hover {
                    transform: scale(1.2);
                }
                
                .whisper-image-btn {
                    background: none;
                    border: none;
                    font-size: 24px;
                    cursor: pointer;
                    padding: 8px;
                    transition: transform 0.2s;
                    display: inline-block;
                }
                
                .whisper-image-btn:hover {
                    transform: scale(1.2);
                }
                
                .image-preview {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 12px;
                    margin-top: 12px;
                }
                
                .image-preview-item {
                    position: relative;
                    width: 100px;
                    height: 100px;
                    border-radius: 8px;
                    overflow: hidden;
                    border: 2px solid #e1e4e8;
                }
                
                .dark .image-preview-item {
                    border-color: #374151;
                }
                
                .image-preview-item img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
                
                .image-preview-remove {
                    position: absolute;
                    top: 4px;
                    right: 4px;
                    background: rgba(0, 0, 0, 0.6);
                    color: white;
                    border: none;
                    border-radius: 50%;
                    width: 24px;
                    height: 24px;
                    cursor: pointer;
                    font-size: 16px;
                    line-height: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                
                .image-preview-remove:hover {
                    background: rgba(220, 38, 38, 0.8);
                }
                
                .emoji-picker {
                    position: absolute;
                    bottom: 100%;
                    left: 0;
                    background: white;
                    border: 1px solid #e1e4e8;
                    border-radius: 12px;
                    padding: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    display: none;
                    grid-template-columns: repeat(10, 1fr);
                    gap: 6px;
                    margin-bottom: 8px;
                    z-index: 100;
                    min-width: 400px;
                }
                
                .dark .emoji-picker {
                    background: #1f2937;
                    border-color: #374151;
                }
                
                .emoji-picker.show {
                    display: grid;
                }
                
                .emoji-item {
                    font-size: 20px;
                    cursor: pointer;
                    padding: 6px;
                    border-radius: 6px;
                    transition: all 0.2s;
                    text-align: center;
                }
                
                .emoji-item:hover {
                    background: #f3f4f6;
                    transform: scale(1.15);
                }
                
                .dark .emoji-item:hover {
                    background: #374151;
                }
                
                @media (max-width: 768px) {
                    .emoji-picker {
                        grid-template-columns: repeat(8, 1fr);
                        min-width: 320px;
                    }
                    
                    .emoji-item {
                        font-size: 18px;
                        padding: 4px;
                    }
                    
                    .whisper-images {
                        grid-template-columns: repeat(2, 1fr) !important;
                    }
                    
                    .whisper-image {
                        height: 150px !important;
                    }
                    
                    .whisper-images:has(:only-child) {
                        max-width: 100%;
                    }
                    
                    .whisper-images:has(:only-child) .whisper-image {
                        height: 250px !important;
                    }
                }
                
                /* 图片灯箱样式 */
                .image-lightbox {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.9);
                    z-index: 9999;
                    justify-content: center;
                    align-items: center;
                }
                
                .image-lightbox.show {
                    display: flex;
                }
                
                .lightbox-content {
                    position: relative;
                    max-width: 90%;
                    max-height: 90%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                
                .lightbox-image {
                    max-width: 100%;
                    max-height: 90vh;
                    object-fit: contain;
                    transition: transform 0.3s ease;
                    cursor: zoom-in;
                }
                
                .lightbox-image.zoomed {
                    cursor: zoom-out;
                    transform: scale(1.5);
                }
                
                .lightbox-close {
                    position: absolute;
                    top: 20px;
                    right: 20px;
                    background: rgba(255, 255, 255, 0.2);
                    border: none;
                    color: white;
                    font-size: 36px;
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background 0.3s;
                    line-height: 1;
                }
                
                .lightbox-close:hover {
                    background: rgba(255, 255, 255, 0.3);
                }
                
                .lightbox-controls {
                    position: absolute;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    display: flex;
                    gap: 12px;
                    background: rgba(0, 0, 0, 0.5);
                    padding: 12px 20px;
                    border-radius: 30px;
                }
                
                .lightbox-btn {
                    background: rgba(255, 255, 255, 0.2);
                    border: none;
                    color: white;
                    font-size: 20px;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background 0.3s;
                }
                
                .lightbox-btn:hover {
                    background: rgba(255, 255, 255, 0.3);
                }
                
                @media (max-width: 768px) {
                    .lightbox-close {
                        top: 10px;
                        right: 10px;
                        width: 40px;
                        height: 40px;
                        font-size: 28px;
                    }
                    
                    .lightbox-controls {
                        bottom: 20px;
                        padding: 8px 16px;
                    }
                    
                    .lightbox-btn {
                        width: 36px;
                        height: 36px;
                        font-size: 18px;
                    }
                }
                
                .whisper-submit-btn {
                    background: linear-gradient(135deg, #5b7ef5 0%, #7c5cfa 100%);
                    color: white;
                    border: none;
                    padding: 10px 32px;
                    border-radius: 8px;
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s;
                }
                
                .whisper-submit-btn:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(91, 126, 245, 0.4);
                }
                
                .whisper-submit-btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }
                
                .whisper-list {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }
                
                .whisper-item {
                    background: white;
                    border: 1px solid #e1e4e8;
                    border-radius: 16px;
                    padding: 20px;
                    transition: all 0.3s;
                    animation: fadeIn 0.5s ease-in;
                }
                
                .dark .whisper-item {
                    background: #1f2937;
                    border-color: #374151;
                }
                
                .whisper-item:hover {
                    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
                    transform: translateY(-2px);
                }
                
                .whisper-header {
                    display: flex;
                    align-items: center;
                    margin-bottom: 12px;
                }
                
                .whisper-avatar {
                    width: 48px;
                    height: 48px;
                    border-radius: 50%;
                    margin-right: 12px;
                }
                
                .whisper-author-info {
                    flex: 1;
                }
                
                .whisper-author-name {
                    font-weight: 600;
                    color: #333;
                    font-size: 15px;
                }
                
                .dark .whisper-author-name {
                    color: #e5e7eb;
                }
                
                .whisper-time {
                    color: #8b949e;
                    font-size: 13px;
                    margin-top: 2px;
                }
                
                .whisper-content {
                    color: #24292f;
                    font-size: 15px;
                    line-height: 1.8;
                    margin-bottom: 12px;
                    white-space: pre-wrap;
                    word-wrap: break-word;
                }
                
                .dark .whisper-content {
                    color: #d1d5db;
                }
                
                .whisper-images {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                    gap: 12px;
                    margin-top: 12px;
                }
                
                .whisper-image {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                    border-radius: 12px;
                    cursor: pointer;
                    transition: transform 0.3s;
                }
                
                .whisper-image:hover {
                    transform: scale(1.05);
                }
                
                /* 单张图片时显示更大 */
                .whisper-images:has(:only-child) {
                    grid-template-columns: 1fr;
                    max-width: 400px;
                }
                
                .whisper-images:has(:only-child) .whisper-image {
                    height: 300px;
                }
                
                /* 两张图片时并排显示 */
                .whisper-images:has(:nth-child(2):last-child) {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                /* 三张图片时特殊布局 */
                .whisper-images:has(:nth-child(3):last-child) {
                    grid-template-columns: repeat(2, 1fr);
                }
                
                .whisper-images:has(:nth-child(3):last-child) .whisper-image:first-child {
                    grid-column: 1 / -1;
                    height: 250px;
                }
                
                .whisper-footer {
                    display: flex;
                    flex-direction: column;
                    gap: 8px;
                    margin-top: 12px;
                    padding-top: 12px;
                    border-top: 1px solid #f0f0f0;
                }
                
                .dark .whisper-footer {
                    border-top-color: #374151;
                }
                
                .whisper-footer-top {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                
                .whisper-os {
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    color: #f59e0b;
                    font-size: 13px;
                }
                
                .whisper-actions {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }
                
                .whisper-like-btn {
                    background: none;
                    border: none;
                    color: #6b7280;
                    cursor: pointer;
                    font-size: 14px;
                    padding: 0;
                    transition: all 0.3s;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                }
                
                .whisper-like-btn:hover {
                    color: #ef4444;
                }
                
                .whisper-like-btn.liked {
                    color: #ef4444;
                }
                
                .whisper-like-btn.liked .like-icon {
                    animation: heartBeat 0.5s ease;
                }
                
                .like-icon {
                    font-size: 18px;
                    transition: transform 0.3s;
                    line-height: 1;
                }
                
                .like-count {
                    font-size: 14px;
                    font-weight: 500;
                    line-height: 1;
                }
                
                .whisper-like-btn:active .like-icon {
                    transform: scale(1.3);
                }
                
                @keyframes heartBeat {
                    0%, 100% { transform: scale(1); }
                    25% { transform: scale(1.3); }
                    50% { transform: scale(1.1); }
                    75% { transform: scale(1.2); }
                }
                
                .whisper-delete-btn {
                    margin-left: auto;
                    background: none;
                    border: none;
                    color: #dc2626;
                    cursor: pointer;
                    font-size: 13px;
                    padding: 4px 12px;
                    border-radius: 4px;
                    transition: background 0.2s;
                }
                
                .whisper-delete-btn:hover {
                    background: #fee2e2;
                }
                
                .whisper-loading {
                    text-align: center;
                    padding: 40px;
                    color: #8b949e;
                }
                
                .whisper-login-tip {
                    text-align: center;
                    padding: 20px;
                    background: #fff3cd;
                    border-radius: 12px;
                    color: #856404;
                    margin-bottom: 20px;
                }
                
                .dark .whisper-login-tip {
                    background: #78350f;
                    color: #fef3c7;
                }
                
                /* 分页样式 */
                .whisper-pagination {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 16px;
                    margin-top: 30px;
                    padding: 20px 0;
                }
                
                .pagination-btn {
                    background: white;
                    border: 1px solid #e1e4e8;
                    color: #333;
                    padding: 10px 24px;
                    border-radius: 8px;
                    font-size: 14px;
                    cursor: pointer;
                    transition: all 0.3s;
                }
                
                .dark .pagination-btn {
                    background: #1f2937;
                    border-color: #374151;
                    color: #e5e7eb;
                }
                
                .pagination-btn:hover:not(:disabled) {
                    background: #5b7ef5;
                    color: white;
                    border-color: #5b7ef5;
                    transform: translateY(-2px);
                }
                
                .pagination-btn:disabled {
                    opacity: 0.5;
                    cursor: not-allowed;
                }
                
                .pagination-info {
                    color: #666;
                    font-size: 14px;
                    min-width: 80px;
                    text-align: center;
                }
                
                .dark .pagination-info {
                    color: #9ca3af;
                }
                
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                @media (max-width: 768px) {
                    .whisper-images {
                        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                    }
                }
                </style>
                
                <div class="whisper-container">
                    <?php $user = Typecho_Widget::widget('Widget_User'); ?>
                    <?php $options = Typecho_Widget::widget('Widget_Options')->plugin('Whisper'); ?>
                    
                    <?php if ($user->hasLogin()): ?>
                        <h1 class="whisper-title"><?php echo $options->pageTitle; ?></h1>
                    <?php else: ?>
                        <?php if ($options->showGuestTitle === '1' && !empty($options->guestTitle)): ?>
                            <h1 class="whisper-title"><?php echo $options->guestTitle; ?></h1>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if ($user->hasLogin()): ?>
                    <div class="whisper-publish">
                        <textarea 
                            id="whisper-content" 
                            class="whisper-textarea" 
                            placeholder="<?php echo Typecho_Widget::widget('Widget_Options')->plugin('Whisper')->placeholder; ?>"
                        ></textarea>
                        <div class="whisper-actions">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="position: relative;">
                                    <button class="whisper-emoji-btn" onclick="toggleEmojiPicker(event)">😊 表情</button>
                                    <div class="emoji-picker" id="emoji-picker"></div>
                                </div>
                                <label class="whisper-image-btn" title="上传图片">
                                    📷 图片
                                    <input type="file" id="image-upload" accept="image/*" multiple style="display: none;" onchange="handleImageUpload(event)">
                                </label>
                            </div>
                            <button class="whisper-submit-btn" onclick="publishWhisper()">立即发表</button>
                        </div>
                        <div id="image-preview" class="image-preview"></div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="whisper-list" id="whisper-list">
                        <div class="whisper-loading">加载中...</div>
                    </div>
                    
                    <!-- 分页 -->
                    <div class="whisper-pagination" id="whisper-pagination" style="display: none;">
                        <button class="pagination-btn" id="prev-btn" onclick="prevPage()">上一页</button>
                        <span class="pagination-info" id="page-info">第 1 页</span>
                        <button class="pagination-btn" id="next-btn" onclick="nextPage()">下一页</button>
                    </div>
                </div>
            </div>
            <!-- 微语内容结束 -->
            
        </div>
    </div>
    <div class="hidden lg:col-span-3 lg:block" id="sidebar-right">
        <?php $this->need('component/sidebar.php'); ?>
    </div>
</div>

<!-- 图片灯箱 -->
<div class="image-lightbox" id="image-lightbox" onclick="closeLightbox(event)">
    <button class="lightbox-close" onclick="closeLightbox(event)">×</button>
    <div class="lightbox-content">
        <img class="lightbox-image" id="lightbox-image" src="" alt="预览" onclick="toggleZoom(event)">
    </div>
    <div class="lightbox-controls">
        <button class="lightbox-btn" onclick="zoomIn(event)" title="放大">+</button>
        <button class="lightbox-btn" onclick="zoomOut(event)" title="缩小">−</button>
        <button class="lightbox-btn" onclick="resetZoom(event)" title="重置">⟲</button>
    </div>
</div>

<script>
const isLogin = <?php echo $user->hasLogin() ? 'true' : 'false'; ?>;
let currentPage = 1;
let totalPages = 1;
let hasMore = true;
let uploadedImages = []; // 存储已上传的图片URL

// 处理图片上传
function handleImageUpload(event) {
    const files = event.target.files;
    if (!files.length) return;
    
    // 限制最多上传9张图片
    if (uploadedImages.length + files.length > 9) {
        alert('最多只能上传9张图片');
        return;
    }
    
    Array.from(files).forEach(file => {
        // 检查文件大小（限制5MB）
        if (file.size > 5 * 1024 * 1024) {
            alert('图片大小不能超过5MB');
            return;
        }
        
        // 检查文件类型
        if (!file.type.startsWith('image/')) {
            alert('只能上传图片文件');
            return;
        }
        
        // 上传图片
        uploadImage(file);
    });
    
    // 清空input，允许重复选择同一文件
    event.target.value = '';
}

// 上传图片到服务器
function uploadImage(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    // 显示上传中的预览
    const reader = new FileReader();
    reader.onload = function(e) {
        addImagePreview(e.target.result, true);
    };
    reader.readAsDataURL(file);
    
    // 使用我们自己的上传接口
    fetch('<?php $this->options->index('/action/whisper?do=upload'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data && data[0] && data[0].url) {
            // 移除上传中的预览
            const previews = document.querySelectorAll('.image-preview-item.uploading');
            if (previews.length > 0) {
                previews[0].remove();
            }
            
            // 添加真实的图片URL
            uploadedImages.push(data[0].url);
            addImagePreview(data[0].url, false);
        } else {
            alert('图片上传失败: ' + (data.message || '未知错误'));
            // 移除失败的预览
            const previews = document.querySelectorAll('.image-preview-item.uploading');
            if (previews.length > 0) {
                previews[0].remove();
            }
        }
    })
    .catch(error => {
        console.error('上传错误:', error);
        alert('图片上传失败，请重试');
        // 移除失败的预览
        const previews = document.querySelectorAll('.image-preview-item.uploading');
        if (previews.length > 0) {
            previews[0].remove();
        }
    });
}

// 添加图片预览
function addImagePreview(url, isUploading) {
    const preview = document.getElementById('image-preview');
    const item = document.createElement('div');
    item.className = 'image-preview-item' + (isUploading ? ' uploading' : '');
    item.innerHTML = `
        <img src="${url}" alt="预览">
        ${!isUploading ? `<button class="image-preview-remove" onclick="removeImage('${url}')">×</button>` : ''}
        ${isUploading ? '<div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:white;font-size:12px;">上传中...</div>' : ''}
    `;
    preview.appendChild(item);
}

// 移除图片
function removeImage(url) {
    uploadedImages = uploadedImages.filter(img => img !== url);
    renderImagePreviews();
}

// 重新渲染图片预览
function renderImagePreviews() {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    uploadedImages.forEach(url => {
        addImagePreview(url, false);
    });
}

// 加载微语列表
function loadWhispers() {
    fetch('<?php $this->options->index('/action/whisper?do=list'); ?>&page=' + currentPage)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderWhispers(data.data);
                updatePagination(data.data.length);
            }
        });
}

// 更新分页按钮
function updatePagination(count) {
    const pageSize = <?php echo Typecho_Widget::widget('Widget_Options')->plugin('Whisper')->pageSize; ?>;
    const pagination = document.getElementById('whisper-pagination');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const pageInfo = document.getElementById('page-info');
    
    // 如果有数据，显示分页
    if (count > 0 || currentPage > 1) {
        pagination.style.display = 'flex';
    } else {
        pagination.style.display = 'none';
        return;
    }
    
    // 更新页码显示
    pageInfo.textContent = `第 ${currentPage} 页`;
    
    // 上一页按钮
    prevBtn.disabled = currentPage <= 1;
    
    // 下一页按钮（如果返回的数据少于每页数量，说明没有下一页了）
    nextBtn.disabled = count < pageSize;
}

// 上一页
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        loadWhispers();
        // 滚动到顶部
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

// 下一页
function nextPage() {
    currentPage++;
    loadWhispers();
    // 滚动到顶部
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// 渲染微语
function renderWhispers(whispers) {
    const listEl = document.getElementById('whisper-list');
    listEl.innerHTML = '';
    
    if (whispers.length === 0) {
        listEl.innerHTML = '<div class="whisper-loading">还没有微语，快来发表第一条吧~</div>';
        return;
    }
    
    whispers.forEach(whisper => {
        const item = document.createElement('div');
        item.className = 'whisper-item';
        
        // 检查是否已点赞
        const isLiked = checkIfLiked(whisper.id);
        
        item.innerHTML = `
            <div class="whisper-header">
                <img src="${whisper.author_avatar}" class="whisper-avatar" alt="${whisper.author_name}">
                <div class="whisper-author-info">
                    <div class="whisper-author-name">${whisper.author_name}</div>
                    <div class="whisper-time">${whisper.time_ago}</div>
                </div>
            </div>
            <div class="whisper-content">${escapeHtml(whisper.content)}</div>
            ${whisper.images_array.length > 0 ? `
                <div class="whisper-images">
                    ${whisper.images_array.map(img => `<img src="${img}" class="whisper-image" onclick="viewImage('${img}')">`).join('')}
                </div>
            ` : ''}
            <div class="whisper-footer">
                <div class="whisper-footer-top">
                    ${whisper.os_info ? `<span class="whisper-os">${whisper.os_info}</span>` : '<span></span>'}
                    ${isLogin ? `<button class="whisper-delete-btn" onclick="deleteWhisper(${whisper.id})">删除</button>` : ''}
                </div>
                <div class="whisper-actions">
                    <button class="whisper-like-btn ${isLiked ? 'liked' : ''}" onclick="likeWhisper(${whisper.id}, this)" ${isLiked ? 'disabled' : ''}>
                        <span class="like-icon">❤️</span>
                        <span class="like-count">${whisper.likes || 0}</span>
                    </button>
                </div>
            </div>
        `;
        listEl.appendChild(item);
    });
}

// 发表微语
function publishWhisper() {
    const content = document.getElementById('whisper-content').value.trim();
    if (!content) {
        alert('请输入内容');
        return;
    }
    
    const btn = document.querySelector('.whisper-submit-btn');
    btn.disabled = true;
    btn.textContent = '发表中...';
    
    // 将图片URL数组转为逗号分隔的字符串
    const imagesStr = uploadedImages.join(',');
    
    fetch('<?php $this->options->index('/action/whisper?do=publish'); ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'content=' + encodeURIComponent(content) + '&images=' + encodeURIComponent(imagesStr)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('whisper-content').value = '';
            uploadedImages = [];
            document.getElementById('image-preview').innerHTML = '';
            loadWhispers();
        } else {
            alert(data.message);
        }
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = '立即发表';
    });
}

// 删除微语
function deleteWhisper(id) {
    if (!confirm('确定要删除这条微语吗？')) return;
    
    fetch('<?php $this->options->index('/action/whisper?do=delete'); ?>&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                loadWhispers();
            } else {
                alert(data.message);
            }
        });
}

// 插入表情
function insertEmoji(emoji) {
    const textarea = document.getElementById('whisper-content');
    textarea.value += emoji;
    textarea.focus();
    // 关闭表情选择器
    document.getElementById('emoji-picker').classList.remove('show');
}

// 切换表情选择器
function toggleEmojiPicker(event) {
    event.stopPropagation();
    const picker = document.getElementById('emoji-picker');
    
    // 如果是第一次打开，初始化表情列表
    if (picker.innerHTML === '') {
        const emojis = [
            '😊', '😂', '🤣', '😍', '😘', 
            '😭', '😢', '😤', '😡', '🤔',
            '😎', '🥰', '😜', '🤗', '🤩',
            '❤️', '💕', '💖', '💗', '💙',
            '👍', '👎', '👏', '🙏', '💪',
            '🎉', '🎊', '🎈', '🎁', '🔥',
            '✨', '⭐', '🌟', '💫', '🌈',
            '☀️', '🌙', '⚡', '💧', '🌸'
        ];
        
        emojis.forEach(emoji => {
            const item = document.createElement('span');
            item.className = 'emoji-item';
            item.textContent = emoji;
            item.onclick = () => insertEmoji(emoji);
            picker.appendChild(item);
        });
    }
    
    picker.classList.toggle('show');
}

// 点击其他地方关闭表情选择器
document.addEventListener('click', function(event) {
    const picker = document.getElementById('emoji-picker');
    if (picker && !event.target.closest('.whisper-emoji-btn')) {
        picker.classList.remove('show');
    }
});

// HTML转义
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// 查看图片
function viewImage(url) {
    const lightbox = document.getElementById('image-lightbox');
    const image = document.getElementById('lightbox-image');
    image.src = url;
    image.style.transform = 'scale(1)';
    image.classList.remove('zoomed');
    lightbox.classList.add('show');
    document.body.style.overflow = 'hidden'; // 禁止背景滚动
}

// 关闭灯箱
function closeLightbox(event) {
    if (event.target.id === 'image-lightbox' || event.target.classList.contains('lightbox-close')) {
        const lightbox = document.getElementById('image-lightbox');
        lightbox.classList.remove('show');
        document.body.style.overflow = ''; // 恢复滚动
    }
}

// 切换缩放
function toggleZoom(event) {
    event.stopPropagation();
    const image = document.getElementById('lightbox-image');
    image.classList.toggle('zoomed');
}

// 放大
function zoomIn(event) {
    event.stopPropagation();
    const image = document.getElementById('lightbox-image');
    const currentScale = parseFloat(image.style.transform.replace('scale(', '').replace(')', '') || '1');
    const newScale = Math.min(currentScale + 0.2, 3); // 最大3倍
    image.style.transform = `scale(${newScale})`;
    image.classList.add('zoomed');
}

// 缩小
function zoomOut(event) {
    event.stopPropagation();
    const image = document.getElementById('lightbox-image');
    const currentScale = parseFloat(image.style.transform.replace('scale(', '').replace(')', '') || '1');
    const newScale = Math.max(currentScale - 0.2, 0.5); // 最小0.5倍
    image.style.transform = `scale(${newScale})`;
    if (newScale === 1) {
        image.classList.remove('zoomed');
    }
}

// 重置缩放
function resetZoom(event) {
    event.stopPropagation();
    const image = document.getElementById('lightbox-image');
    image.style.transform = 'scale(1)';
    image.classList.remove('zoomed');
}

// ESC键关闭灯箱
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const lightbox = document.getElementById('image-lightbox');
        if (lightbox.classList.contains('show')) {
            lightbox.classList.remove('show');
            document.body.style.overflow = '';
        }
    }
});

// 检查是否已点赞
function checkIfLiked(id) {
    return document.cookie.indexOf('whisper_liked_' + id + '=') !== -1;
}

// 点赞微语
function likeWhisper(id, btn) {
    if (btn.disabled) return;
    
    btn.disabled = true;
    
    fetch('<?php $this->options->index('/action/whisper?do=like'); ?>&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // 更新点赞数
                const countEl = btn.querySelector('.like-count');
                countEl.textContent = data.likes;
                
                // 添加已点赞样式
                btn.classList.add('liked');
                
                // 触发心跳动画
                const icon = btn.querySelector('.like-icon');
                icon.style.animation = 'none';
                setTimeout(() => {
                    icon.style.animation = 'heartBeat 0.5s ease';
                }, 10);
            } else {
                alert(data.message);
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('点赞失败:', error);
            alert('点赞失败，请重试');
            btn.disabled = false;
        });
}

// 页面加载时获取微语
loadWhispers();
</script>

<?php $this->need('footer.php'); ?>
</body>
</html>
