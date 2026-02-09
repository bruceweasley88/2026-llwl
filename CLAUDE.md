# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## 项目概述

这是一个全栈单体仓库，包含三个独立但相互关联的项目：

- **`admin/`** - Vue 3 + Vite 管理后台（Element Plus UI）
- **`server/`** - PHP ThinkPHP 8 后端 API 服务
- **`uniapp/`** - UniApp 跨平台移动应用（Vue 2）

三个前端（admin、uniapp、PC web）共享同一个后端 API。

---

## 开发命令

### Admin（Vue 3 管理后台）

```bash
cd admin

# 开发
npm run dev

# 构建
npm run build

# 类型检查
npm run type-check

# 代码检查
npm run lint
```

### Server（ThinkPHP 8 后端）

```bash
cd server

# 启动内置服务器（开发）
php think run

# 安装依赖
composer install

# 清除缓存
php think clear

# 生成服务发现
php think service:discover
```

### UniApp（移动应用）

```bash
cd uniapp

# 注意：项目目前缺少 package.json，需要先初始化
npm init -y
npm install @dcloudio/uni-cli-vue @vue/cli-service -D

# 微信小程序开发
npm run dev:mp-weixin

# H5 开发
npm run dev:h5

# 构建
npm run build:mp-weixin  # 微信小程序
npm run build:h5         # H5
```

---

## 后端架构（ThinkPHP 8）

### 多应用结构

```
server/app/
├── adminapi/    # 管理后台 API
├── api/         # 移动端 API
├── index/       # Web/PC 端 API
└── common/      # 共享代码
```

### 分层架构

每个应用遵循以下分层结构：

```
app/{application}/
├── controller/      # HTTP 请求处理（薄层）
├── logic/           # 业务逻辑层（核心）
├── validate/        # 输入验证
├── lists/           # 列表查询处理器
├── service/         # 领域服务
└── http/middleware/ # 应用中间件
```

**关键原则：**
- **Controller** 只处理 HTTP 关注点，不包含业务逻辑
- **Logic** 封装可复用的业务规则
- **Validate** 独立验证类，支持场景验证
- **Service** 处理跨领域关注点（文件、支付、令牌等）

### 认证与授权

**Token 认证机制：**
- Token 通过自定义请求头 `token` 传递（非 `Authorization`）
- 支持多终端登录（同一用户不同设备不同 token）
- **自动续期**：临近过期时自动延长（管理后台）
- Token 存储在 `AdminSession`/`UserSession` 模型

**中间件管道：**
```php
1. LikeAdminAllowMiddleware  # CORS 处理
2. BaseMiddleware            # 基础初始化
3. InitMiddleware            # 请求初始化
4. LoginMiddleware           # 认证检查
5. AuthMiddleware            # 权限验证
```

### API 响应标准

所有 API 响应遵循统一结构：

```php
{
    "code": 1,        // 1=成功, 0=失败, -1=token过期
    "show": 1,        // 是否显示消息
    "msg": "success",
    "data": {}
}
```

使用 `JsonService` 类生成响应：
- `JsonService::success($msg, $data)` - 成功响应
- `JsonService::fail($msg, $code)` - 失败响应
- `JsonService::data($data)` - 仅数据响应
- `JsonService::dataLists($lists, $count)` - 分页列表响应

### 数据库与 ORM

**ThinkORM 模式：**
- 使用 `SoftDelete` trait 实现软删除
- Accessor/Mutator 自动处理图片 URL
- Searcher 定义查询作用域
- 关联关系在模型中定义

**模型位置：** `app/common/model/`

### 配置管理

关键配置文件：
- `config/project.php` - 应用设置（token 过期时间、登录限制等）
- `config/database.php` - 数据库连接
- `config/filesystem.php` - 文件存储（支持 OSS、COS、七牛云）

---

## 前端架构（Admin - Vue 3）

### 技术栈

- **构建工具：** Vite 6.x
- **UI 框架：** Element Plus 2.9
- **状态管理：** Pinia 2.3
- **路由：** Vue Router 4.5
- **HTTP：** Axios（自定义封装）

### 项目结构

```
admin/src/
├── api/              # API 客户端函数（按资源分离）
├── components/       # 可复用组件
├── config/           # 应用配置
├── enums/            # TypeScript 枚举
├── hooks/            # Composition API hooks
├── layout/           # 布局组件
├── router/           # 路由配置
├── stores/           # Pinia 状态存储
├── utils/            # 工具函数
└── views/            # 页面组件
```

### 认证流程

```
登录 → 存储 token → Axios 拦截器自动注入 →
请求后端 → 响应拦截器处理：
  - code 1: 成功 → 返回数据
  - code 0: 失败 → 显示错误
  - code -1: token 过期 → 清除认证，跳转登录
```

### 动态路由与权限

1. 登录后调用 `getUserInfo()` 获取菜单和权限
2. `filterAsyncRoutes()` 将后端路由转换为 Vue Router
3. 使用 `import.meta.glob()` 实现懒加载
4. 权限指令：`v-perms="['admin:user:add']"`

### Axios 封装特性

- 自动注入 token 到请求头
- 重复请求取消
- GET 请求失败自动重试
- 统一错误处理
- NProgress 进度条

---

## 移动端架构（UniApp - Vue 2）

### UniApp 特性

单一代码库可编译到：
- iOS/Android 原生应用
- H5 网页应用
- 微信/支付宝/百度/抖音小程序

### 项目结构

```
uniapp/
├── pages/           # 页面组件
├── components/      # 可复用组件
├── static/          # 静态资源
├── api/             # API 客户端函数
├── manifest.json    # 应用配置（AppID、权限等）
└── pages.json       # 路由和导航配置
```

### 关键差异

与 Admin 前端的主要区别：
- **无 Axios**：使用 `uni.request()`
- **无状态管理库**：使用组件状态 + localStorage
- **Token 手动注入**：每个请求手动添加 token
- **无自动续期**：token 过期需重新登录

### UniApp 特有模式

**条件编译：**
```javascript
// #ifdef H5
// H5 特定代码
// #endif

// #ifndef VUE3
// 非 Vue 3 代码
// #endif
```

**响应式单位：**
- 使用 `rpx`（响应式像素）
- 750rpx = 屏幕宽度

**组件替换：**
- `<view>` 代替 `<div>`
- `<text>` 代替 `<span>`
- `<image>` 代替 `<img>`

### 路由配置

所有页面必须在 `pages.json` 中注册：
```json
{
    "pages": [
        {
            "path": "pages/index/index",
            "style": {
                "navigationBarTitleText": "首页"
            }
        }
    ]
}
```

### 生命周期钩子

- **应用级**（App.vue）：`onLaunch`、`onShow`、`onHide`
- **页面级**：`onLoad`、`onShow`、`onReady`、`onHide`、`onUnload`

### 全局 SCSS 变量

`uni.scss` 中的变量自动可用：
- `$uni-color-primary`、`$uni-color-success`、`$uni-color-warning`、`$uni-color-error`
- `$uni-text-color`、`$uni-bg-color`、`$uni-spacing-row-base`

---

## API 通信模式

### Admin → Backend

```
组件 → API 函数 → Axios 拦截器 →
添加 token → 后端 →
LoginMiddleware → 验证 token →
Controller → Logic → Model →
响应 → Axios 拦截器 →
转换数据 → 组件
```

### Mobile → Backend

```
组件 → API 函数 → uni.request →
手动添加 token → 后端 →
LoginMiddleware → 验证 token →
Controller → Logic → Model →
响应 → 组件
```

### 错误处理

| 响应码 | 含义 | 前端处理 |
|--------|------|----------|
| 1 | 成功 | 返回 data |
| 0 | 失败 | 显示错误消息，拒绝 promise |
| -1 | Token 过期 | 清除认证，跳转登录页 |

---

## 开发工作流

### 添加新功能（管理后台）

1. 创建视图组件：`admin/src/views/{module}/{page}.vue`
2. 创建 API 函数：`admin/src/api/{module}.ts`
3. 后端创建 Controller action
4. 后端创建/更新 Logic 类
5. 后端更新 Validate 类
6. 测试认证和权限

### 添加新功能（移动端）

1. 创建页面组件：`uniapp/pages/{page}/{page}.vue`
2. 在 `pages.json` 中注册路由
3. 创建 API 函数：`uniapp/api/{module}.js`
4. 复用后端 API 端点
5. 在目标平台测试

---

## 重要配置说明

### Admin Vite 配置

- 基础路径：`/admin/`（子目录部署）
- 自动导入：Vue、VueRouter API
- 组件自动注册：Element Plus 组件
- SVG 图标：转换为 symbol sprites

### UniApp manifest.json

- `mp-weixin.appid` - 微信小程序 AppID（必须配置）
- `appid` - DCloud AppID（原生应用）
- `vueVersion: "2"` - 当前使用 Vue 2

### 后端中间件

全局中间件（`app/middleware.php`）：
- `LikeAdminAllowMiddleware` - CORS 处理
- `BaseMiddleware` - 基础初始化

应用中间件在各应用的 `middleware.php` 中定义。

---

## 代码规范

### PHP
- 遵循 PSR-4 自动加载
- Controller 使用 `array $notNeedLogin` 定义无需登录的方法
- Logic 类处理所有业务逻辑

### Vue 3（Admin）
- 使用 Composition API
- TypeScript 类型安全
- 组件使用 PascalCase
- API 函数按资源分离

### Vue 2（UniApp）
- 使用 Options API
- 文件使用 kebab-case
- 资源引用使用绝对路径（如 `/static/logo.png`）
- 优先使用 flexbox 布局

---

## 第三方集成

### 云存储支持
- 阿里云 OSS
- 腾讯云 COS
- 七牛云

### 支付集成
- 支付宝 SDK（`alipaysdk/easysdk`）
- 微信支付（`w7corp/easywechat`）

### 短信服务
- 腾讯云 SMS（`tencentcloud/sms`）

---

## 部署架构

**后端：**
- 服务根目录：`/server/public/`
- 多个前端 SPA 从子目录提供服务：
  - `/admin/` - 管理后台
  - `/mobile/` - 移动端 H5
  - `/pc/` - PC Web

**前端构建输出：**
- Admin: `dist/` → 部署到 `/admin/`
- UniApp: `unpackage/dist/` → 按平台部署
