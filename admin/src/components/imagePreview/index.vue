<template>
    <div class="image-preview-container">
        <div class="image-list" v-if="imageList.length > 0">
            <el-image v-for="(img, index) in imageList" :key="index" :src="getFullUrl(img)"
                :preview-src-list="fullImageUrlList" :initial-index="index" fit="cover"
                class="preview-image" />
        </div>
        <div v-else class="empty-text">暂无图片</div>
    </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

interface Props {
    value: string
    baseUrl: string
}

const props = defineProps<Props>()

// 解析图片列表
const imageList = computed(() => {
    if (!props.value) return []
    return props.value.split(',').map(url => url.trim()).filter(url => url)
})

// 获取完整URL列表用于预览
const fullImageUrlList = computed(() => {
    return imageList.value.map(img => getFullUrl(img))
})

// 获取完整URL
const getFullUrl = (url: string) => {
    if (!url) return ''
    // 如果已经是完整URL，直接返回
    if (url.startsWith('http://') || url.startsWith('https://')) {
        return url
    }

    // 智能拼接 baseUrl 和 url，避免重复斜杠
    // baseUrl 格式: http://img/ 或 http://img
    // url 格式: /php84B2.tmp
    if (props.baseUrl) {
        // 移除 baseUrl 末尾的斜杠
        const base = props.baseUrl.replace(/\/+$/, '')
        // url 带前导斜杠，直接拼接
        return base + url
    }

    // 如果没有 baseUrl，直接返回 url（浏览器会自动用当前域名）
    return url
}
</script>

<style scoped>
.image-preview-container {
    width: 100%;
}

.image-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.preview-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    cursor: pointer;
}

.empty-text {
    color: #909399;
    font-size: 14px;
    padding: 10px 0;
}
</style>
