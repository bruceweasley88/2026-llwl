# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

这是一个基于 **Vue 2** 的 **UniApp 跨平台移动应用**，属于一个全栈单体仓库的一部分，包括：
- `admin/` - Vue 3 管理后台（基于 Vite）
- `server/` - PHP ThinkPHP 后端
- `uniapp/` - 本 UniApp 移动应用

UniApp 可以用一套代码编译到多个平台：
- iOS 和 Android 原生应用
- H5（网页浏览器）
- 微信、支付宝、百度和头条小程序

## 开发命令

**注意：** 此项目目前缺少 `package.json` 文件。要启用完整的开发工具链，需要初始化 npm：

```bash
npm init -y
npm install @dcloudio/uni-cli-vue @vue/cli-service -D
```

配置完成后，常用的开发命令为：
- `npm run dev:mp-weixin` - 运行微信小程序开发服务器
- `npm run dev:h5` - 运行 H5 开发服务器
- `npm run build:mp-weixin` - 构建微信小程序生产版本
- `npm run build:h5` - 构建 H5 生产版本

## 架构说明

### 入口文件
1. **index.html** - H5 构建的 HTML 入口，挂载到 `<div id="app">`
2. **main.js** - JavaScript 入口，初始化 Vue 2 应用
3. **App.vue** - 根组件，包含应用生命周期钩子（`onLaunch`、`onShow`、`onHide`）
4. **pages.json** - 页面路由和导航配置

### 应用流程
```
index.html → main.js → App.vue → pages.json 路由 → 各个页面组件
```

### 配置文件
- **manifest.json** - 应用元数据、平台特定配置、权限和 SDK 设置
- **pages.json** - 路由注册、导航栏样式和全局样式
- **uni.scss** - 全局 SCSS 变量（自动在所有组件中可用）

### 项目结构
```
uniapp/
├── App.vue                  # 根组件
├── main.js                  # JS 入口文件
├── manifest.json            # 应用/平台配置
├── pages.json               # 路由配置
├── uni.scss                 # 全局 SCSS 变量
├── uni.promisify.adaptor.js # uni API 的 Promise 适配器
├── pages/                   # 页面组件
│   └── index/
│       └── index.vue       # 首页
└── static/                  # 静态资源（图片、字体）
    └── logo.png
```

## UniApp 特有模式

### 条件编译
使用预处理器指令编写平台特定代码：
```javascript
// #ifndef VUE3  // 如果不是 Vue 3
// #ifdef VUE3   // 如果是 Vue 3
// #endif        // 结束条件块
```

### 自定义组件
用 UniApp 组件替换标准 HTML 元素：
- 使用 `<view>` 代替 `<div>`
- 使用 `<text>` 代替 `<span>`
- 使用 `<image>` 代替 `<img>`

### 响应式单位
使用 **rpx**（响应式像素）实现响应式尺寸：
- `rpx` 相对于屏幕宽度缩放（750rpx = 屏幕宽度）
- 自动适配不同设备尺寸

### API Promise 化
`uni.promisify.adaptor.js` 包装器将回调风格的 API 转换为基于 Promise 的：
```javascript
import uni promisify from '@/uni.promisify.adaptor'

// 返回 [error, result] 数组模式
const [err, res] = await uni.request({ url: '...' })
```

### 生命周期钩子
- **应用级**（App.vue）：`onLaunch`、`onShow`、`onHide`
- **页面级**（页面组件）：`onLoad`、`onShow`、`onReady`、`onHide`、`onUnload`
- 遵循 Vue 2 选项 API 模式

### 全局 SCSS 变量
`uni.scss` 中预定义的变量无需引入即可使用：
- 颜色：`$uni-color-primary`、`$uni-color-success`、`$uni-color-warning`、`$uni-color-error`
- 文字颜色：`$uni-text-color`、`$uni-text-color-grey`
- 背景色：`$uni-bg-color`、`$uni-bg-color-grey`
- 间距：`$uni-spacing-row-base`、`$uni-spacing-col-base`
- 圆角：`$uni-border-radius-base`

### 导航配置
所有页面必须在 `pages.json` 中注册：
```json
{
  "pages": [
    {
      "path": "pages/index/index",
      "style": {
        "navigationBarTitleText": "页面标题"
      }
    }
  ]
}
```

## 重要配置说明

### AppID 配置
`manifest.json` 中的 `appid` 字段为空，必须进行配置：
- **mp-weixin.appid** - 微信小程序 AppID（微信构建必需）
- **appid**（根级别）- 原生应用的 DCloud AppID

### Vue 版本
此项目使用 **Vue 2**（manifest.json 中 `"vueVersion": "2"`）。要升级到 Vue 3：
1. 更新 `manifest.json` → `"vueVersion": "3"`
2. 修改 `main.js` 使用 Vue 3 的 `createSSRApp`
3. 重构组件为组合式 API（可选但推荐）

### 平台权限
Android 权限已在 `manifest.json` 中预配置，包括相机、网络、电话状态、WiFi、手电筒等。请根据实际应用需求审查和调整。

## 代码规范

- **Vue 2 选项 API**：使用 `export default { data(), methods: {}, onLoad() {} }`
- **文件命名**：文件使用 kebab-case（`index.vue`），组件使用 PascalCase
- **资源引用**：使用 `/static` 文件夹的绝对路径（如 `/static/logo.png`）
- **布局**：优先使用 flexbox 进行布局（参考 `pages/index/index.vue`）
