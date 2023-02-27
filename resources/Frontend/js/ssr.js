import { createInertiaApp, Head, Link } from "@inertiajs/inertia-vue3";
import createServer from "@inertiajs/server";
import { renderToString } from "@vue/server-renderer";
import SvgVue from "svg-vue3";
import { createSSRApp, h } from "vue";
import route from "ziggy-js";
import AnimateAppear from "./Components/AnimateAppear.vue";
import JButton from "./Components/jam/Button.vue";
import JIcon from "./Components/jam/Icon.vue";
import JImage from "./Components/jam/Image.vue";
import SocialMeta from "./Components/SocialMeta.vue";
import JPicture from "./Components/jam/Picture.vue";
import JField from "./Components/jam/Field.vue";
import JFieldSet from "./Components/jam/FieldSet.vue";
import Slider from "./Components/jam/Slider.vue";
import Slide from "./Components/jam/Slide.vue";
import { ObserveVisibility } from "vue3-observe-visibility";

createServer(
    (page) =>
        createInertiaApp({
            page,
            render: renderToString,
            resolve: (name) => require(`./Pages/${name}`),
            setup({ app, props, plugin }) {
                const Ziggy = {
                    ...props.initialPage.props.ziggy,
                    location: new URL(props.initialPage.props.ziggy.url),
                };

                return createSSRApp({
                    render: () => h(app, props),
                })
                    .use(plugin)
                    .use(SvgVue)
                    .mixin({
                        methods: {
                            route: (name, params, absolute, config = Ziggy) =>
                                route(name, params, absolute, config),
                        },
                    })
                    .directive("observe-visibility", ObserveVisibility)
                    .component("Link", Link)
                    .component("Head", Head)
                    .component("JButton", JButton)
                    .component("JImage", JImage)
                    .component("JPicture", JPicture)
                    .component("JIcon", JIcon)
                    .component("JFieldSet", JFieldSet)
                    .component("JField", JField)
                    .component("SocialMeta", SocialMeta)
                    .component("AnimateAppear", AnimateAppear)
                    .component("Slider", Slider)
                    .component("Slide", Slide);
            },
        }),
    13716
);
