<template>
  <header class="relative z-30" @scroll="handleWindowScroll">
    <div
      class="fake-header h-[90px] md:h-[114px] lg:h-[138px] xl:h-[183px]"
    ></div>
    <div
      id="start-sticky-view"
      class="fixed inset-x-0 top-0 transition-all duration-300 translate-y-0 bg-white"
    >
      <div class="top-header bg-red-550">
        <div class="container hidden md:block">
          <a
            href="/"
            class="flex flex-wrap items-center justify-center py-2 text-center transition duration-200 ease-in-out md:py-3 hover:opacity-70"
          >
            <JIcon name="truck" class="w-6 h-6 text-white mb-0.5" />
            <strong
              class="btn-md rounded-full bg-white text-red-550 px-2.5 pt-[4px] pb-[4px] ml-1.5 mr-0.5 xs:mr-1.5"
              >Miễn phí vận chuyển</strong
            >
            <strong class="text-white btn-md sm:mt-0.5"
              ><span class="max-md:hidden">cho</span> đơn hàng trên 700K</strong
            >
          </a>
        </div>
        <div class="block md:hidden">
          <JPicture
            src="/images/header/mb-ads.jpg"
            class="object-cover w-full h-full"
          />
        </div>
      </div>
      <div class="main-header shadow-1 xl:shadow-none">
        <div class="container">
          <div
            class="relative flex items-center justify-between py-2 lg:py-5 md:justify-start"
          >
            <div
              class="hamburger xl:hidden h-[32px] w-[32px] flex items-center justify-center"
              @click="openMenu()"
            >
              <div class="hamburger__icon h-[24px] w-[24px]">
                <div></div>
                <div></div>
                <div></div>
              </div>
            </div>
            <div
              class="relative z-10 ml-2 mr-auto md:ml-4 md:hidden popout-menu"
              :class="showBlog ? 'active' : ''"
              @click="showBlog = !showBlog"
            >
              <div class="block">
                <JIcon name="newspaper" class="text-gray-700 w-7 h-7" />
              </div>
              <div class="absolute left-0 top-full popout-content-mobile">
                <div class="mt-[10px] shadow-1 overflow-y-auto max-h-[450px]">
                  <Link
                    v-for="(category, idx) in blogCategory"
                    :key="idx"
                    :href="
                      route('post.category-show', {
                        categorySlug: category.slug,
                      })
                    "
                    class="block w-[269px] md:py-4 py-2 px-5 font-bold hover:bg-gray-200 transition-all ease-in-out duration-200 bg-white"
                    :class="
                      checkActive(category.slug) ? 'text-red-500' : 'text-black'
                    "
                    >{{ category.title }}</Link
                  >
                </div>
              </div>
            </div>
            <Link
              href="/"
              class="absolute mr-auto -translate-x-1/2 -translate-y-1/2 sm:mx-0 md:ml-4 left-1/2 top-1/2 md:translate-x-0 md:translate-y-0 md:static"
            >
              <Logo class="text-[#000000]" />
            </Link>
            <div
              class="relative flex-1 hidden ml-4 bg-gray-100 rounded-full md:block"
            >
              <JIcon
                name="search"
                class="absolute text-gray-500 -translate-y-1/2 cursor-pointer left-5 top-1/2"
                @click="onSearchProduct"
              />
              <input
                v-model="key"
                class="w-full py-3 pl-12 pr-3 font-semibold text-gray-600 bg-transparent border-0 search-input font-display focus:ring-0 focus:outline-none"
                placeholder="Tìm kiếm"
                @keyup.enter="onSearchProduct"
                @focusin="isShowSearchPc = true"
                @focusout="hideSearchPc()"
              />

              <div
                v-if="
                  ((instantProducts && instantProducts.length > 0) ||
                    (instantSuggestions && instantSuggestions.length > 0)) &&
                  isShowSearchPc
                "
                class="absolute w-full bg-white mt-[4px] max-h-[368px] rounded-[8px] shadow-lg py-[12px] z-[100] overflow-y-auto"
              >
                <div
                  v-for="(suggestion, index) in instantSuggestions"
                  :key="index"
                  class="px-[24px] py-[10px] caption text-red-900 font-semibold cursor-pointer"
                  @click="
                    {
                      (key = suggestion), onSearchProduct();
                    }
                  "
                >
                  {{ suggestion }}
                </div>
                <Link
                  v-for="(product, index) in instantProducts"
                  :key="index"
                  :href="
                    route('product.show', {
                      productSlug: product.slug,
                      productId: product.id,
                    })
                  "
                  class="px-[24px] py-[10px] caption text-red-900 font-semibold flex space-x-[24px] items-center hover:bg-gray-100"
                >
                  <div class="aspect-[1/1] w-[32px]">
                    <JImage
                      :src="
                        product.image_url
                          ? product.image_url
                          : '/images/cover.jpg'
                      "
                      :alt="product.name"
                      class="object-cover w-full h-full"
                    />
                  </div>

                  <div class="line-clamp-1">
                    {{ product.name }}
                  </div>
                </Link>
              </div>
            </div>
            <div class="flex items-center space-x-2.5 md:space-x-4">
              <div class="hidden ml-4 caption xl:block">
                <div class="vertical-custom-slide">
                  <div class="vertical-move">
                    <a
                      href="#"
                      class="transition-opacity duration-200 ease-in-out vertical-slide-item caption hover:opacity-70"
                    >
                      <span class="font-semibold text-red-450"
                        >Giao hàng tiết kiệm
                      </span>
                      <span class="font-medium text-black"
                        >cho đơn hàng trên 500K</span
                      >
                    </a>
                    <a
                      href="#"
                      class="transition-opacity duration-200 ease-in-out vertical-slide-item caption hover:opacity-70"
                    >
                      <span class="font-semibold text-red-450"
                        >GIẢM THÊM 10%
                      </span>
                      <span class="font-medium text-black"
                        >ĐH ĐẦU TIÊN TỪ 600K</span
                      >
                    </a>
                    <a
                      href="#"
                      class="transition-opacity duration-200 ease-in-out vertical-slide-item caption hover:opacity-70"
                    >
                      <span class="font-semibold text-red-450"
                        >THANH TOÁN KHI NHẬN HÀNG</span
                      >
                    </a>
                  </div>
                </div>
              </div>
              <div v-click-outside="hideSearch" class="md:hidden">
                <div
                  class="search-wrap"
                  :class="isShowSearch ? 'search-active' : ''"
                >
                  <div class="relative flex-1 bg-gray-100 rounded-full md:ml-4">
                    <input
                      v-model="key"
                      class="w-full py-3 pl-3 pr-12 font-semibold text-gray-600 bg-transparent border-0 search-input font-display focus:ring-0 focus:outline-none"
                      placeholder="Tìm kiếm"
                      @keyup.enter="onSearchProduct"
                      @focusin="isShowSearchPc = true"
                      @focusout="hideSearchPc()"
                    />
                    <div
                      class="absolute -translate-y-1/2 cursor-pointer right-[12px] top-1/2 p-[10px] bg-red-500 rounded-full"
                      @click="onSearchProduct"
                    >
                      <JIcon name="search" class="text-white" />
                    </div>
                    <div
                      v-if="
                        ((instantProducts && instantProducts.length > 0) ||
                          (instantSuggestions &&
                            instantSuggestions.length > 0)) &&
                        isShowSearchPc
                      "
                      class="absolute w-full bg-white mt-[4px] max-h-[368px] rounded-[8px] shadow-lg py-[12px] z-[100] overflow-y-auto"
                    >
                      <div
                        v-for="(suggestion, index) in instantSuggestions"
                        :key="index"
                        class="px-[24px] py-[10px] caption text-red-900 font-semibold cursor-pointer"
                        @click="
                          {
                            (key = suggestion), onSearchProduct();
                          }
                        "
                      >
                        {{ suggestion }}
                      </div>
                      <Link
                        v-for="(product, index) in instantProducts"
                        :key="index"
                        :href="
                          route('product.show', {
                            productSlug: product.slug,
                            productId: product.id,
                          })
                        "
                        class="px-[24px] py-[10px] caption text-red-900 font-semibold flex space-x-[24px] items-center hover:bg-gray-100"
                      >
                        <div class="aspect-[1/1] w-[32px]">
                          <JImage
                            :src="
                              product.image_url
                                ? product.image_url
                                : '/images/cover.jpg'
                            "
                            :alt="product.name"
                            class="object-cover w-full h-full"
                          />
                        </div>

                        <div class="line-clamp-1">
                          {{ product.name }}
                        </div>
                      </Link>
                    </div>
                  </div>
                </div>
                <a
                  href="javascript:;"
                  class="flex items-center"
                  @click="showSearch"
                >
                  <JIcon name="search" class="w-6 h-6 text-gray-700" />
                </a>
              </div>
              <div
                class="relative z-10 popout-menu"
                :class="showCart ? 'active' : ''"
                @mouseenter="showCart = true"
                @mouseout="showCart = false"
              >
                <Link
                  :href="route('checkout.cart.index')"
                  class="flex items-center text-gray-800 transition-colors duration-200 ease-in-out group hover:text-red-400"
                >
                  <div class="relative">
                    <JIcon
                      name="handbag"
                      class="text-gray-700 transition-colors duration-200 ease-in-out w-7 h-7 xl:w-6 xl:h-6 group-hover:text-red-400"
                    />
                    <span
                      class="absolute top-[-5px] right-[-7px] flex items-center justify-center border border-white w-4 h-4 rounded-full bg-red-500 text-[10px] leading-[10px] p-0.5 font-extrabold text-white font-display"
                    >
                      {{
                        cart && cart.total_quantity ? cart.total_quantity : 0
                      }}</span
                    >
                  </div>
                  <span
                    class="hidden ml-1 font-semibold xl:block font-display mt-0.5"
                    >Giỏ hàng</span
                  >
                </Link>
                <div
                  v-if="cart && cart.items && cart.items.length > 0"
                  class="absolute right-0 md:-right-16 top-full popout-content"
                >
                  <div class="mt-5 bg-white md:p-6 shadow-1 cart-pointer">
                    <div class="max-h-[60vh] overflow-y-auto">
                      <div class="p-4">
                        <Link
                          v-for="(item, idx) in cart.items"
                          :key="idx"
                          :href="
                            route('product.show', {
                              productSlug: item.product_detail.slug,
                              productId: item.product_detail.id,
                            })
                          "
                          class="flex min-w-[260px] sm:min-w-[377px] hover:bg-gray-100 transition-all ease-in-out duration-200 bg-white mb-4"
                        >
                          <div class="w-[70px] h-[70px] relative">
                            <JImage
                              class="absolute inset-0 object-cover w-full h-full"
                              :src="
                                item.product_detail.image_url
                                  ? item.product_detail.image_url
                                  : '/images/cover.jpg'
                              "
                              :alt="item.product_detail.name"
                            />
                          </div>
                          <div class="flex-1 ml-1 mr-4">
                            <span
                              class="mb-1 font-medium text-gray-800 caption line-clamp-2"
                            >
                              {{ item.product_detail.name }}
                            </span>
                            <span class="font-medium text-gray-500 caption"
                              >SL x {{ item.qty }}
                            </span>
                          </div>
                          <strong class="ml-auto text-black caption">
                            {{ toMoney(item.price) }}
                          </strong>
                        </Link>
                      </div>
                    </div>
                    <div class="py-1"></div>
                    <div class="pt-4 border-t border-gray-400 border-dashed">
                      <Link
                        :href="route('checkout.cart.index')"
                        class="flex items-center justify-center no-underline cursor-pointer overflow-hidden z-10 relative text-white border bg-red-400 transition-all duration-[400] ease-in-out h-[38px] btn-sm hover:bg-black rounded-sm"
                      >
                        <span>XEM GIỎ HÀNG VÀ THANH TOÁN</span>
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
              <a
                href="tel:0981064444"
                class="items-center hidden text-gray-800 transition-colors duration-200 ease-in-out group md:flex hover:text-red-400"
              >
                <JIcon
                  name="headset-sm"
                  class="text-gray-700 transition-colors duration-200 ease-in-out w-7 h-7 xl:w-6 xl:h-6 group-hover:text-red-400"
                />
                <span
                  class="hidden ml-1 font-semibold xl:block font-display mt-0.5"
                  >Hotline</span
                >
              </a>
              <div
                class="relative z-10 hidden md:block popout-menu"
                :class="showBlog ? 'active' : ''"
                @mouseenter="showBlog = true"
                @mouseout="showBlog = false"
              >
                <Link
                  :href="route('post.index')"
                  class="flex items-center text-gray-800 transition-colors duration-200 ease-in-out group hover:text-red-400"
                >
                  <JIcon
                    name="newspaper"
                    class="text-gray-700 transition-colors duration-200 ease-in-out w-7 h-7 xl:w-6 xl:h-6 group-hover:text-red-400"
                  />
                  <span
                    class="ml-1 font-semibold hidden xl:block font-display mt-0.5"
                    >Blog</span
                  >
                </Link>
                <div class="absolute right-0 top-full popout-content">
                  <div class="mt-3 shadow-1">
                    <Link
                      v-for="(category, idx) in blogCategory"
                      :key="idx"
                      :href="
                        route('post.category-show', {
                          categorySlug: category.slug,
                        })
                      "
                      class="block w-[269px] md:py-4 py-2 px-5 font-bold hover:bg-gray-200 transition-all ease-in-out duration-200 bg-white"
                      :class="
                        checkActive(category.slug)
                          ? 'text-red-500'
                          : 'text-black'
                      "
                      >{{ category.title }}</Link
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Menu desktop -->
      <div class="hidden bg-black bottom-header xl:block">
        <div class="container">
          <div
            class="flex lg:items-center xl:justify-center space-x-4 2xl:space-x-[22px] px-4 xl:px-0 relative"
          >
            <Link
              :href="route('new.index')"
              class="px-2 py-1 font-bold text-red-400 transition-all duration-200 ease-in-out bg-white caption hover:bg-red-400 hover:text-white"
              >NEW</Link
            >
            <Link
              :href="route('sale.index')"
              class="px-2 py-1 font-bold text-white transition-all duration-200 ease-in-out bg-red-400 caption hover:bg-white hover:text-red-400"
              >SALE SỐC</Link
            >
            <div
              v-for="(menu, index) of navs"
              :key="index"
              class="category"
              @mouseover="menuIndex = menu.id"
              @mouseleave="menuIndex = null"
            >
              <Link
                :href="menu.url"
                class="inline-block py-3 font-bold text-white transition-all duration-200 ease-in-out menu-link caption hover:text-red-400"
              >
                {{ menu.title }}
              </Link>
              <div
                v-if="menu.child && menu.child.length"
                class="absolute left-0 right-0 top-full sub-menu"
                :class="menuIndex == menu.id ? '' : 'hidden'"
              >
                <div
                  class="relative flex items-start pt-6 pb-12 space-x-3 2xl:space-x-4"
                >
                  <Link
                    :href="menu.link_header_left"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                  >
                    <JPicture
                      :src="
                        menu.left_banner_url
                          ? menu.left_banner_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                  <div class="grid flex-1 grid-cols-4 gap-x-3 2xl:gap-x-4">
                    <div
                      v-for="(col, idx) of menu.child"
                      :key="idx"
                      class="block"
                    >
                      <div class="mb-0.5">
                        <Link
                          :href="col.url"
                          class="inline-block font-bold text-black caption link-effect"
                          >{{ col.title }}</Link
                        >
                      </div>
                      <div class="block">
                        <template v-if="col.child && col.child.length">
                          <div
                            v-for="(item, idxItem) of col.child"
                            :key="idxItem"
                            class="mb-0.5"
                          >
                            <Link
                              :href="item.url"
                              class="inline-block text-black caption link-effect"
                              >{{ item.title }}</Link
                            >
                          </div>
                        </template>
                      </div>
                    </div>
                  </div>
                  <Link
                    :href="menu.link_header_center"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                  >
                    <JPicture
                      :src="
                        menu.center_banner_url
                          ? menu.center_banner_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                  <Link
                    :href="menu.link_header_right"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                  >
                    <JPicture
                      :src="
                        menu.right_banner_url
                          ? menu.right_banner_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                </div>
              </div>
            </div>
            <!-- Render Brand -->
            <div
              class="category"
              @mouseover="menuIndex = 9999"
              @mouseleave="menuIndex = null"
            >
              <Link
                :href="route('brand.index')"
                class="inline-block py-3 font-bold text-white menu-link caption"
              >
                {{ categoriesBrand.name }}
              </Link>
              <div
                v-if="
                  categoriesBrand.gridColItems &&
                  categoriesBrand.gridColItems.length
                "
                class="absolute left-0 right-0 top-full sub-menu"
                :class="menuIndex == 9999 ? '' : 'hidden'"
              >
                <div
                  class="relative flex items-start pt-6 pb-12 space-x-3 2xl:space-x-4"
                >
                  <Link
                    v-if="
                      categoriesBrand.links && categoriesBrand.links.length > 0
                    "
                    :href="categoriesBrand.links[0].url"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                    :target="categoriesBrand.links[0].target"
                  >
                    <JPicture
                      :src="
                        categoriesBrand.links[0].image_url
                          ? categoriesBrand.links[0].image_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                  <div class="grid flex-1 grid-cols-4 gap-x-3 2xl:gap-x-4">
                    <div
                      v-for="(col, idx) of categoriesBrand.gridColItems"
                      :key="idx"
                      class="block"
                    >
                      <template v-for="(submenu, index) of col" :key="index">
                        <div class="mb-0.5">
                          <Link
                            :href="
                              route('products.index', {
                                slug: submenu.slug,
                              })
                            "
                            class="inline-block text-black caption link-effect"
                            >{{ submenu.name }}</Link
                          >
                        </div>
                      </template>
                    </div>
                  </div>
                  <Link
                    v-if="
                      categoriesBrand.links && categoriesBrand.links.length > 1
                    "
                    :href="categoriesBrand.links[1].url"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                    :target="categoriesBrand.links[1].target"
                  >
                    <JPicture
                      :src="
                        categoriesBrand.links[1].image_url
                          ? categoriesBrand.links[1].image_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                  <Link
                    v-if="
                      categoriesBrand.links && categoriesBrand.links.length > 2
                    "
                    :href="categoriesBrand.links[2].url"
                    class="w-[198px] hover:opacity-70 transition-opacity duration-200 ease-in-out"
                    :target="categoriesBrand.links[2].target"
                  >
                    <JPicture
                      :src="
                        categoriesBrand.links[2].image_url
                          ? categoriesBrand.links[2].image_url
                          : '/images/cover.jpg'
                      "
                      class="picture-cover"
                    />
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Menu mobile -->
    <div class="menus xl:hidden">
      <div
        class="fixed inset-0 bg-black bg-opacity-40 overlay"
        @click="closeMenu()"
      ></div>
      <div class="menus-content">
        <div class="relative h-full overflow-hidden">
          <div class="main-menu">
            <div class="close-section">
              <a
                href="javascript:;"
                class="flex items-center justify-between text-black transition hover:text-brown-600"
                @click="closeMenu()"
              >
                <span class="font-bold p3">DANH MỤC</span>
                <JIcon name="close" />
              </a>
            </div>
            <div class="overflow-y-auto wrap-content">
              <div class="py-[7px] border-b border-gray-200">
                <Link
                  class="px-4 py-[7px] flex items-center text-red-400 lg:hover:text-black transition-colors"
                  :href="route('sale.index')"
                >
                  <span class="flex items-center flex-1">
                    <JIcon name="fire" />
                    <span class="ml-2 font-semibold p4">SALE SỐC</span>
                  </span>
                </Link>
                <Link
                  class="px-4 py-[7px] flex items-center text-red-400 lg:hover:text-black transition-colors"
                  :href="route('new.index')"
                >
                  <span class="flex items-center flex-1">
                    <JIcon name="confetti" />
                    <span class="ml-2 font-semibold p4">NEW</span>
                  </span>
                </Link>
                <template v-for="(menu, idx) of navs" :key="idx">
                  <a
                    v-if="menu.child && menu.child.length"
                    class="px-4 py-[7px] flex items-center text-black lg:hover:text-red-400 transition-colors"
                    href="javascript:;"
                    @click="(menuMbIndex = idx), (currentSubMenu = menu.title)"
                  >
                    <span class="flex items-center flex-1">
                      <span class="w-[26px] h-26px]" v-html="menu.icon"></span>
                      <span class="ml-2 font-semibold p4">{{
                        menu.title
                      }}</span>
                    </span>
                    <JIcon name="arrow-right" class="text-gray-500" />
                  </a>
                  <Link
                    v-else
                    :href="menu.url"
                    class="px-4 py-[7px] flex items-center text-black lg:hover:text-red-400 transition-colors"
                  >
                    <span class="flex items-center flex-1">
                      <span class="w-[26px] h-26px]" v-html="menu.icon"></span>
                      <span class="ml-2 font-semibold p4">{{
                        menu.title
                      }}</span>
                    </span>
                  </Link>
                </template>
                <!-- Render Brands -->
                <a
                  v-if="categoriesBrand.items && categoriesBrand.items.length"
                  class="px-4 py-[7px] flex items-center text-black lg:hover:text-red-400 transition-colors"
                  href="javascript:;"
                  @click="
                    (menuMbBrandIndex = categoriesBrand.id),
                      (currentSubMenu = categoriesBrand.name)
                  "
                >
                  <span class="flex items-center flex-1">
                    <span
                      class="w-[26px] h-26px]"
                      v-html="categoriesBrand.icon"
                    ></span>
                    <span class="ml-2 font-semibold p4">{{
                      categoriesBrand.name
                    }}</span>
                  </span>
                  <JIcon name="arrow-right" class="text-gray-500" />
                </a>
                <Link
                  v-else
                  :href="
                    route('products.index', {
                      slug: categoriesBrand.slug,
                    })
                  "
                  class="px-4 py-[7px] flex items-center text-black lg:hover:text-red-400 transition-colors"
                >
                  <span class="flex items-center flex-1">
                    <span
                      class="w-[26px] h-26px]"
                      v-html="categoriesBrand.icon"
                    ></span>
                    <span class="ml-2 font-semibold p4">{{
                      categoriesBrand.name
                    }}</span>
                  </span>
                </Link>
              </div>
              <div class="py-[7px] border-b border-gray-200">
                <p
                  class="px-4 py-[7px] flex items-center flex-1 text-gray-800 font-bold"
                >
                  <JIcon name="headset-sm" class="mr-2 text-gray-400" />
                  Hotline&nbsp;<a
                    href="tel:0981064444"
                    class="transition-colors text-red-450 lg:hover:text-black"
                    >098.106.4444</a
                  >
                </p>
                <Link
                  :href="route('post.index')"
                  class="px-4 py-[7px] flex items-center flex-1 text-gray-800 font-bold lg:hover:text-red-450 transition-colors"
                >
                  <JIcon name="newspaper" class="mr-2 text-gray-400" />
                  Blog
                </Link>
              </div>
              <div class="px-4 pt-3.5 pb-6">
                <strong class="text-gray-800"
                  ><span class="text-red-450">Giao hàng tiết kiệm</span> cho đơn
                  hàng trên 500K</strong
                >
              </div>
            </div>
          </div>
          <div
            class="menu-group"
            :class="
              menuMbIndex != null || menuMbBrandIndex != null
                ? 'left-0'
                : 'left-full'
            "
          >
            <div class="close-section">
              <div
                class="flex items-center justify-between text-black transition hover:text-brown-600"
              >
                <a
                  href="javascript:;"
                  class="flex items-center flex-1"
                  @click="
                    (menuMbIndex = null),
                      (menuMbBrandIndex = null),
                      (currentSubMenu = null)
                  "
                >
                  <JIcon name="arrow-left" class="mr-4 text-black" />
                  <span class="font-bold p3">{{ currentSubMenu }}</span>
                </a>
                <a href="javascript:;" class="ml-3" @click="closeMenu()">
                  <JIcon name="close" />
                </a>
              </div>
            </div>
            <div class="overflow-y-auto wrap-content">
              <div class="py-3.5 px-6 border-b border-gray-200">
                <template v-if="menuMbIndex != null">
                  <Link
                    v-if="
                      menuMbIndex != null && navs[menuMbIndex].left_banner_url
                    "
                    class="block transition-opacity lg:hover:opacity-70"
                    :href="navs[menuMbIndex].link_header_left"
                  >
                    <JPicture
                      v-if="menuMbIndex != null"
                      :src="navs[menuMbIndex].left_banner_url"
                      class="picture-cover"
                    />
                  </Link>
                </template>
                <template v-else-if="menuMbBrandIndex != null">
                  <Link
                    v-if="
                      menuMbBrandIndex != null && categoriesBrand.links.length
                    "
                    class="block transition-opacity lg:hover:opacity-70"
                    :href="categoriesBrand.links[0].url"
                    :target="categoriesBrand.links[0].target"
                  >
                    <JPicture
                      v-if="menuMbBrandIndex != null"
                      :src="categoriesBrand.links[0].image_url"
                      class="picture-cover"
                    />
                  </Link>
                </template>
              </div>
              <template v-for="(menu, idx) of navs" :key="idx">
                <div
                  v-if="menu.child && menu.child.length"
                  :class="menuMbIndex == idx ? '' : 'hidden'"
                >
                  <div class="mb-3.5">
                    <Collapse
                      v-for="(submenu, index) of menu.child"
                      :key="index"
                      class="py-2"
                    >
                      <template #collapse-trigger>
                        <h4
                          class="flex items-center px-6 font-bold text-black transition-colors lg:hover:text-red-400"
                        >
                          <span class="flex-1 mr-4">{{ submenu.title }}</span>
                          <div class="w-5 h-5 collapse-icon-2">
                            <div></div>
                            <div></div>
                          </div>
                        </h4>
                      </template>
                      <template #collapse-content>
                        <div class="mt-2">
                          <div @click="closeMenu()">
                            <Link
                              :href="submenu.url"
                              class="text-black py-[7px] px-6 block lg:hover:text-red-400 transition-colors font-medium"
                            >
                              Xem tất cả
                            </Link>
                          </div>
                          <div
                            v-for="(item, index) of submenu.child"
                            :key="index"
                            @click="closeMenu()"
                          >
                            <Link
                              :href="item.url"
                              class="text-black py-[7px] px-6 block lg:hover:text-red-400 transition-colors font-medium"
                            >
                              {{ item.title }}
                            </Link>
                          </div>
                        </div>
                      </template>
                    </Collapse>
                    <!-- <Accordions>
                    </Accordions> -->
                  </div>
                  <div class="px-6 pt-3.5 pb-6" @click="closeMenu()">
                    <Link
                      v-if="menuMbIndex != null"
                      :href="navs[menuMbIndex].url"
                      class="flex items-center justify-center no-underline cursor-pointer overflow-hidden z-10 relative text-black border border-gray-800 bg-transparent duration-[400] ease-in-out h-[44px] lg:hover:text-red-400 lg:hover:border-red-400 transition-all leading-[14px] font-bold"
                    >
                      <span>TẤT CẢ SẢN PHẨM</span>
                    </Link>
                  </div>
                </div>
              </template>
              <!-- Render Brand -->
              <div
                v-if="categoriesBrand.items && categoriesBrand.items.length"
                :class="menuMbBrandIndex == categoriesBrand.id ? '' : 'hidden'"
              >
                <div class="py-3 mb-3.5 border-b border-gray-200">
                  <div
                    v-for="(item, index) of categoriesBrand.items.slice(0, 10)"
                    :key="index"
                    @click="closeMenu()"
                  >
                    <Link
                      :href="
                        route('products.index', {
                          slug: item.slug,
                        })
                      "
                      class="text-black py-[7px] px-6 block font-medium"
                    >
                      {{ item.name }}
                    </Link>
                  </div>
                </div>
                <div class="px-6 pt-3.5 pb-6" @click="closeMenu()">
                  <Link
                    v-if="menuMbBrandIndex != null"
                    :href="
                      route('products.index', {
                        slug: categoriesBrand.slug,
                      })
                    "
                    class="flex items-center justify-center no-underline cursor-pointer overflow-hidden z-10 relative text-black border border-gray-800 bg-transparent transition-all duration-[400] ease-in-out h-[44px] leading-[14px] font-bold"
                  >
                    <span>TẤT CẢ SẢN PHẨM</span>
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div
    class="fixed z-[100] hidden bg-white border border-gray-100 rounded lg:block top-[100px] popup-cart-success box-shadow duration-300"
    :class="
      isCartSuccess && showPopupCart
        ? 'translate-x-0 opacity-100 right-[3rem]'
        : 'translate-x-full opacity-0 right-0'
    "
    @mouseover="
      isCartSuccess = true;
      isMouseOverPopupCart = true;
    "
    @mouseleave="
      isCartSuccess = false;
      isMouseOverPopupCart = false;
    "
  >
    <div class="p-6 min-w-[326px]">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <JIcon name="tick_cart" class="mr-1" />
        </div>
        <div class="font-bold text-gray-900">Thêm vào giỏ hàng thành công</div>
      </div>
    </div>
  </div>
</template>

<script>
import Logo from "./Logo.vue";
import Collapse from "./Collapse.vue";
import axios from "axios";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    Logo,
    Collapse,
  },
  directives: {
    "click-outside": {
      beforeMount: (el, binding) => {
        el.clickOutsideEvent = (event) => {
          // here I check that click was outside the el and his children
          if (!(el == event.target || el.contains(event.target))) {
            // and if it did, call method provided in attribute value
            binding.value();
          }
        };
        document.addEventListener("click", el.clickOutsideEvent);
      },
      unmounted: (el) => {
        document.removeEventListener("click", el.clickOutsideEvent);
      },
    },
  },
  props: {
    showPopupCart: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      cart: null,
      previousTop: window.scrollY,
      showSticky: true,
      isShowSearch: false,
      isShowSearchPc: false,
      showCart: false,
      showBlog: false,
      categoriesBrand: [],
      column: 4,
      currentSubMenu: null,
      isOpened: false,
      menuIndex: null,
      menuMbIndex: null,
      menuMbBrandIndex: null,
      menus: {},
      key: null,
      isCartSuccess: false,
      isMouseOverPopupCart: false,
      timeOut: 0,
      instantProducts: [],
      instantSuggestions: [],
    };
  },
  computed: {
    blogCategory() {
      return this.$page.props.blog;
    },

    navs() {
      return this.$page.props.navs;
    },
  },
  watch: {
    "$page.url"() {
      this.key = null;
      this.isShowSearch = false;
      this.isShowSearchPc = false;
      this.closeMenu();
    },

    key() {
      const self = this;
      clearTimeout(this.timeOut);

      this.timeOut = setTimeout(function () {
        self.getInstantSearch();
      }, 300);
    },
  },
  created() {
    this.menus = JSON.parse(JSON.stringify(this.$page.props.menu));
    this.setBrand();
  },

  mounted() {
    const self = this;

    window.addEventListener("scroll", this.handleWindowScroll);
    this.startStickyView = document.getElementById("start-sticky-view");

    this.$bus.on("updateCart", function (data) {
      self.cart = data;
      if (data.redirect) {
        Inertia.get(data.redirect);
      }
    });
    // cartSuccess
    self.$bus.on("cartSuccess", function () {
      let timeoutID;
      self.isCartSuccess = true;

      function timeoutCB() {
        if (self.isMouseOverPopupCart) {
          clearTimeout(timeoutID);
          return;
        }
        self.isCartSuccess = false;
        clearTimeout(timeoutID);
      }
      timeoutID = setTimeout(timeoutCB, 2500);
    });
  },
  methods: {
    checkActive(url) {
      const fullPath = this.$page.props.ziggy.url;
      const getPath = fullPath.split("/");
      return url === getPath[4];
    },
    handleWindowScroll() {
      const elem = document.getElementById("start-sticky-view");
      if (
        window.scrollY > this.previousTop &&
        window.scrollY >= this.startStickyView.offsetHeight
      ) {
        this.showSticky = false;
        elem.style.transform = "translateY(-183px)";
      } else {
        this.showSticky = true;
        elem.style.transform = "translateY(0px)";
      }
      this.previousTop = window.scrollY;
    },
    showSearch() {
      this.isShowSearch = true;
    },
    hideSearch() {
      this.isShowSearch = false;
    },
    hideSearchPc() {
      setTimeout(() => {
        this.isShowSearchPc = false;
      }, 300);
    },
    openMenu() {
      const el = document.body;
      el.classList.add("overflow-hidden", "menu-is-opened");
    },
    closeMenu() {
      const el = document.body;
      el.classList.remove("overflow-hidden", "menu-is-opened");
      this.menuMbIndex = null;
      this.menuMbBrandIndex = null;
    },
    setBrand() {
      this.categoriesBrand = JSON.parse(JSON.stringify(this.menus.brands));

      const count = this.categoriesBrand.items.length;
      const itemInCol = Math.ceil(count / this.column);

      this.categoriesBrand["gridColItems"] = [];
      for (let index = 0; index < count; index += itemInCol) {
        const item = this.categoriesBrand.items.slice(index, index + itemInCol);
        this.categoriesBrand["gridColItems"].push(item);
      }
    },

    onSearchProduct() {
      if (this.key === null) {
        return;
      }

      this.$inertia.get(this.route("product.search", { keyword: this.key }));
    },

    async getInstantSearch() {
      if (this.key) {
        const { data } = await axios.get(this.route(`instant-search`), {
          params: {
            keyword: this.key,
          },
        });
        this.instantProducts = data.products;
        this.instantSuggestions = data.suggestions;
      } else {
        this.instantProducts = [];
        this.instantSuggestions = [];
      }
    },
  },
};
</script>
<style>
body {
  --header-height-sm: 60px;
  --header-height-md: 70px;
  --header-height-lg: 80px;
}
</style>

<style lang="scss" scoped>
.search-input {
  &::placeholder {
    /* Chrome, Firefox, Opera, Safari 10.1+ */
    color: theme("colors.gray.600");
    opacity: 1; /* Firefox */
    font-weight: 600;
  }

  &:-ms-input-placeholder,
  &::-ms-input-placeholder {
    /* Microsoft Edge */
    color: theme("colors.gray.600");
    font-weight: 600;
  }
}
.hamburger {
  &__icon {
    @apply flex flex-col justify-center cursor-pointer;

    div {
      width: 100%;
      height: 2px;
      margin-bottom: 3px;
      margin-top: 3px;
      @apply bg-gray-700 transition ease-out duration-200;
    }

    .menu-is-opened & {
      div {
        margin-bottom: 0;
        margin-top: 0;

        &:first-child {
          transform: translateY(calc(50% + 2px)) rotate(45deg);
        }

        &:nth-child(2) {
          opacity: 0;
          width: 0px;
        }

        &:last-child {
          transform: translateY(calc(50% - 2px)) rotate(-45deg);
        }
      }
    }
  }
}
.vertical-custom-slide {
  @apply h-[21px] overflow-hidden;
  .vertical-move {
    @apply inline-flex flex-col relative top-0 left-0 h-full text-center;
    animation: slide-vertical linear 10s infinite;
    &:hover {
      animation-play-state: paused;
    }
  }
}

@keyframes slide-vertical {
  /* use keyframes to shift slides */
  0% {
    top: 0;
  }
  33% {
    top: -100%;
  }
  66% {
    top: -200%;
  }
  100% {
    top: 0;
  }

  /* but the above will shift non-stop */
  /* so we add pauses between each slide */
  0% {
    top: 0;
  }
  30% {
    top: 0;
  }
  33% {
    top: -100%;
  }
  63% {
    top: -100%;
  }
  66% {
    top: -200%;
  }
  97% {
    top: -200%;
  }
  100% {
    top: 0;
  }
}

.search-wrap {
  @apply absolute inset-0 flex items-center justify-center md:static bg-white md:bg-transparent z-[-1] opacity-0 transition-all ease-in-out duration-200 flex-1 md:opacity-100;
  &.search-active {
    @apply z-[20] opacity-100;
    &:before {
      @apply bg-white w-screen absolute inset-y-0;
      content: "";
      left: calc((100% - 100vw) / 2);
    }
  }
}

.sub-menu {
  &:before {
    @apply absolute inset-y-0 shadow-1 bg-white border border-gray-100;
    content: "";
    left: calc((100% - 100vw) / 2);
    right: calc((100% - 100vw) / 2);
  }
}

.link-effect {
  @apply relative;
  &:before {
    @apply absolute w-0 h-px inset-x-0 bottom-0 bg-[#000000] transition-all ease-in-out duration-200;
    content: "";
  }
  &:hover {
    &:before {
      @apply w-full;
    }
  }
}

.menu-link {
  @apply relative;
  &:after,
  &:before {
    @apply absolute left-1/2 -translate-x-1/2 opacity-0 transition-all z-[2];
    content: "";
  }
  &:before {
    @apply bottom-0;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-bottom: 7px solid theme("colors.gray.100");
  }
  &:after {
    @apply -bottom-px;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-bottom: 7px solid theme("colors.white");
  }
}

.category {
  &:hover {
    .menu-link {
      &:after,
      &:before {
        @apply opacity-100;
      }
    }
  }
}

.menus {
  @apply fixed left-0 z-[15] top-0 h-full;
  .menus-content {
    @apply bg-white top-0 left-[-84%] xs:left-[-315px] transition-all fixed z-[2] w-[84%] h-full xs:w-[315px];
  }
  .main-menu {
    @apply h-full;
  }
  .menu-group {
    @apply absolute inset-y-0 transition-all w-full bg-white;
  }
  .overlay {
    @apply hidden;
  }
  .close-section {
    @apply px-4 pt-6 pb-3.5 border-b border-gray-200;
  }

  .wrap-content {
    height: calc(100% - 66px);
  }

  .menu-is-opened & {
    @apply w-full;
    .overlay {
      @apply block;
    }
    .menus-content {
      @apply left-0;
    }
  }
}

.popout-menu {
  .popout-content {
    @apply hidden;
  }
  &.active,
  &:hover {
    .popout-content {
      @apply md:block hidden;
    }
  }

  .popout-content-mobile {
    @apply hidden;
  }

  &.active {
    .popout-content-mobile {
      @apply block;
    }
  }
}

.cart-pointer {
  @apply relative;
  &:before {
    @apply absolute bottom-full right-0.5 md:right-16 xl:right-24;
    content: "";
    border-left: 14px solid transparent;
    border-right: 14px solid transparent;
    border-bottom: 15px solid theme("colors.white");
  }
}

@screen md {
  .menus {
    .menus-content {
      @apply top-0 h-full border-0;
    }
    .menu-is-opened & {
      .overlay {
        @apply block;
      }
    }
  }
}
.box-shadow {
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
}
</style>
