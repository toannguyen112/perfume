<template>
  <nav
    class="sidebar w-[var(--sidebar-width)] h-screen bg-gray-900 flex flex-col fixed"
  >
    <div class="flex items-center justify-between px-3 py-5 text-white">
      <Icon name="logo" />
    </div>
    <div class="py-6 space-y-4">
      <div class="space-y-4">
        <ul>
          <li>
            <Link
              :href="route('sidebar.dashboard.index')"
              class="nav-link"
              :class="{ active: isUrl('dashboard') }"
            >
              <Icon name="dashboard" />
              <span>Tổng Quan</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Sản phẩm</li>
          <li v-if="hasAnyPermission(['sidebar.products.index'])">
            <Link
              :href="route('sidebar.products.index')"
              class="nav-link"
              :class="{ active: isUrl('products') }"
            >
              <Icon name="product" />
              <span>Sản phẩm</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.options.index'])">
            <Link
              :href="route('sidebar.options.index')"
              class="nav-link"
              :class="{ active: isUrl('options') }"
            >
              <Icon name="attribute" />
              <span>Thuộc tính</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.product-categories.index'])">
            <Link
              :href="route('sidebar.product-categories.index')"
              class="nav-link"
              :class="{ active: isUrl('product-categories') }"
            >
              <Icon name="category" />
              <span>Danh mục</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.brands.index'])">
            <Link
              :href="route('sidebar.brands.index')"
              class="nav-link"
              :class="{ active: isUrl('brands') }"
            >
              <Icon name="brand" />
              <span>Thương hiệu</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.tags.index'])">
            <Link
              :href="route('sidebar.tags.index')"
              class="nav-link"
              :class="{ active: isUrl('tags') }"
            >
              <Icon name="tags" />
              <span>Tags</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.sales.index'])">
            <Link
              :href="route('sidebar.sales.index')"
              class="nav-link"
              :class="{ active: isUrl('sales') }"
            >
              <Icon name="sale" />
              <span>Sales</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.use-cases.index'])">
            <Link
              :href="route('sidebar.use-cases.index')"
              class="nav-link"
              :class="{ active: isUrl('use-cases') }"
            >
              <Icon name="sale" />
              <span>Cách sử dụng</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.comments.index'])">
            <Link
              :href="route('sidebar.comments.index')"
              class="nav-link"
              :class="{ active: isUrl('comments') }"
            >
              <Icon name="start" />
              <span>Bình luận</span>
              <span class="badge badge-danger" v-if="newComment">{{
                newComment
              }}</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.orders.index'])">
            <Link
              :href="route('sidebar.orders.index')"
              class="nav-link"
              :class="{ active: isUrl('orders') }"
            >
              <Icon name="order" />
              <span>Đơn hàng</span>
              <span class="badge badge-danger" v-if="newOrder">{{
                newOrder
              }}</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Nội dung</li>
          <li v-if="hasAnyPermission(['sidebar.post-categories.index'])">
            <Link
              :href="route('sidebar.post-categories.index')"
              class="nav-link"
              :class="{ active: isUrl('post-categories') }"
            >
              <Icon name="post-category" />
              <span>Danh mục bài viết</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.posts.index'])">
            <Link
              :href="route('sidebar.posts.index')"
              class="nav-link"
              :class="{ active: isUrl('posts') }"
            >
              <Icon name="post" />
              <span>Bài viết</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.sliders.index'])">
            <Link
              :href="route('sidebar.sliders.index')"
              class="nav-link"
              :class="{ active: isUrl('sliders') }"
            >
              <Icon name="slider" />
              <span>Slider</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.policies.index'])">
            <Link
              :href="route('sidebar.policies.index')"
              class="nav-link"
              :class="{ active: isUrl('policies') }"
            >
              <Icon name="policy" />
              <span>Chính sách</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.jobs.index'])">
            <Link
              :href="route('sidebar.jobs.index')"
              class="nav-link"
              :class="{ active: isUrl('jobs') }"
            >
              <Icon name="job" />
              <span>Tuyển dụng</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.contacts.index'])">
            <Link
              :href="route('sidebar.contacts.index')"
              class="nav-link"
              :class="{ active: isUrl('contacts') }"
            >
              <Icon name="contact" />
              <span>Liên hệ</span>
              <span class="badge badge-danger" v-if="newContact">{{
                newContact
              }}</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.applies.index'])">
            <Link
              :href="route('sidebar.applies.index')"
              class="nav-link"
              :class="{ active: isUrl('applies') }"
            >
              <Icon name="job-form" />
              <span>Form Tuyển dụng</span>
            </Link>
          </li>
        </ul>
        <ul>
          <li class="nav-header">Cài đặt</li>
          <li v-if="hasAnyPermission(['sidebar.seo.index'])">
            <Link
              :href="route('sidebar.seo.index')"
              class="nav-link"
              :class="{ active: isUrl('seo') }"
            >
              <Icon name="category" />
              <span>SEO</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.navs.index'])">
            <Link
              :href="route('sidebar.navs.index')"
              class="nav-link"
              :class="{ active: isUrl('navs') }"
            >
              <Icon name="category" />
              <span>Menu</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.configs.index'])">
            <Link
              :href="route('sidebar.configs.index')"
              class="nav-link"
              :class="{ active: isUrl('configs') }"
            >
              <Icon name="config" />
              <span>Configs</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.media.index'])">
            <Link
              :href="route('sidebar.media.index')"
              class="nav-link"
              :class="{ active: isUrl('media') }"
            >
              <Icon name="media" />
              <span>Media</span>
            </Link>
          </li>

          <!-- <li v-if="hasAnyPermission(['sidebar.users.index'])">
            <Link
              :href="route('sidebar.users.index')"
              class="nav-link"
              :class="{ active: isUrl('users') }"
            >
              <Icon name="user" />
              <span>Thành viên</span>
            </Link>
          </li> -->
          <li>
            <Link
              class="nav-link"
              :href="route('sidebar.admins.index')"
              :class="{ active: isUrl('admins') }"
            >
              <Icon name="account" />
              <span>Tài khoản</span>
            </Link>
          </li>
          <li v-if="hasAnyPermission(['sidebar.roles.index'])">
            <Link
              class="nav-link"
              :href="route('sidebar.roles.index')"
              :class="{ active: isUrl('roles') }"
            >
              <Icon name="rule" />
              <span>Vai trò</span>
            </Link>
          </li>
        </ul>
      </div>
    </div>
    <ul class="mt-auto">
      <li>
        <Link
          :href="route('logout')"
          method="post"
          as="button"
          class="nav-link"
        >
          <Icon name="logout" />
          <span>Đăng xuất</span></Link
        >
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      currentDomain: null,
      permissions: this.$page.props.auth.permissions,
    };
  },
  computed: {
    newOrder() {
      return this.$page.props.new_order_count;
    },
    newContact() {
      return this.$page.props.new_contact_count;
    },
    newComment() {
      return this.$page.props.new_comment_count;
    },
  },
  mounted() {
    this.currentDomain = window.location.hostname;
  },

  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(7);
      if (urls[0] === "") {
        return currentUrl === "";
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length;
    },

    hasAnyPermission(action) {
      if (Array.isArray(action)) {
        return this.permissions.some((r) => action.indexOf(r) >= 0);
      }
      return this.permissions.includes(action);
    },
  },
};
</script>

<style lang="scss" scoped>
.main-wrapper {
  display: flex;
  & > main {
    flex-grow: 1;
    width: auto;
  }
}

.sidebar {
  .nav-link {
    svg {
      @apply w-[20px];
    }
    @apply flex items-center w-full gap-2 px-6 py-2 text-white transition duration-150 text-base;
    &:not(.active) {
      @apply opacity-70;
      &:hover {
        @apply opacity-100;
      }
    }
    &.active {
      color: theme("colors.primary.DEFAULT");
      @apply bg-gray-800;
    }
  }

  .nav-header {
    @apply list-none px-6 py-2 uppercase text-xs  text-primary-darker;
    &:not(:first-of-type) {
      margin-top: 0.5rem;
    }
  }
}
</style>
