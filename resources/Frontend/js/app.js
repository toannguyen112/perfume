import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import SvgVue from "svg-vue3";
import { createApp, h } from "vue";
import { TinyEmitter } from "tiny-emitter";
import AnimateAppear from "./Components/AnimateAppear.vue";
import JButton from "./Components/jam/Button.vue";
import JIcon from "./Components/jam/Icon.vue";
import JImage from "./Components/jam/Image.vue";
import JPicture from "./Components/jam/Picture.vue";
import JField from "./Components/jam/Field.vue";
import JFieldSet from "./Components/jam/FieldSet.vue";
import BProductOption from "./Components/blocks/ProductOption.vue";
import BProductOptionItem from "./Components/blocks/ProductOptionItem.vue";
import SocialMeta from "./Components/SocialMeta.vue";
import { ObserveVisibility } from "vue3-observe-visibility";
import Slider from "./Components/jam/Slider.vue";
import Slide from "./Components/jam/Slide.vue";
const emitter = new TinyEmitter();

InertiaProgress.init();

createInertiaApp({
    resolve: async (name) => {
        return (await import(`./Pages/${name}`)).default;
    },
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(SvgVue)
            .directive("observe-visibility", ObserveVisibility)
            .component("Link", Link)
            .component("Head", Head)
            .component("JButton", JButton)
            .component("JImage", JImage)
            .component("JPicture", JPicture)
            .component("SocialMeta", SocialMeta)
            .component("JIcon", JIcon)
            .component("JFieldSet", JFieldSet)
            .component("JField", JField)
            .component("BProductOption", BProductOption)
            .component("BProductOptionItem", BProductOptionItem)
            .component("AnimateAppear", AnimateAppear)
            .component("SocialMeta", SocialMeta)
            .component("Slider", Slider)
            .component("Slide", Slide)
            .mixin({ methods: { route } })
            .mixin({
                methods: {
                    route,
                    toMoney: function (value) {
                        const formatter = new Intl.NumberFormat("vi", {
                            style: "currency",
                            currency: "VND",
                            minimumFractionDigits: 0,
                        });

                        return formatter.format(value);
                    },
                },
            });

        vueApp.config.globalProperties.$bus = emitter;
        vueApp.mount(el);

        return vueApp;
    },
});
