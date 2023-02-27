const puppeteer = require("puppeteer-extra");
const StealthPlugin = require("puppeteer-extra-plugin-stealth");
puppeteer.use(StealthPlugin());

const fs = require("fs");
const xlsx = require("node-xlsx");

const sheets = xlsx.parse(`data.input.xlsx`);
let urls = [];
let buffer;

let data = [
    [
        "URL",
        "Trạng thái",
        "Nguồn",
        "SizeGuide",
        "SizeGuideInfo",
        "SizeList",
        "Danh mục",
        "Tags",
        "Meta",
        "ID Biến thể",
        "ID Sản phẩm",
        "ID Danh mục",
        "SKU",
        "Tên sản phẩm",
        "Hãng",
        "Hình ảnh",
        "Bộ ảnh",
        "Loại biến thể",
        "Màu sắc",
        "Hình ảnh Màu sắc",
        "Giá",
        "Giá so sánh",
        "Raw",
    ],
];

sheets.forEach((sheet) => {
    sheet.data.forEach((row) => {
        if (row.length) {
            row.forEach((cell) => {
                if (cell.length && cell.includes("https")) {
                    urls.push(cell);
                }
            });
        }
    });
});

const sheinUrls = urls.filter((x) => x.includes("shein.com.vn"));
const sephoraUrls = urls.filter((x) => x.includes("sephora.com"));

(async () => {
    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();
    await page.setUserAgent(
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36"
    );
    await page.setViewport({
        width: 1366,
        height: 768,
    });

    const exportFile = async (data) => {
        buffer = xlsx.build([{ name: "Sản phẩm", data }]);
        return await fs.writeFile("data.output.xlsx", buffer, function (err) {
            if (err) throw err;
            console.log("Saved");
        });
    };

    const transformVariant = (item, album = {}) => {
        return {
            variantId: item.goods_id,
            productId: item.productRelationID ?? item.goods_relation_id,
            categoryId: item.cat_id,
            sku: item.goods_sn,
            name: item.goods_name,
            brand: item.brand,
            image: item.original_img,
            album: JSON.stringify(album),
            type: !!album.length,
            colorText: item.mainSaleAttribute.attr_value,
            colorImage: item.color_image,
            price: item.retailPrice.amount,
            oldPrice: item.salePrice.amount,
            raw: JSON.stringify(item),
        };
    };

    const transformCategories = (item, categories = []) => {
        categories.push(item.multi.cat_name);
        if (item.children.length) {
            return transformCategories(item.children[0], categories);
        } else {
            return categories.join(",");
        }
    };

    const transformTags = (item) => {
        if (!item) return null;
        return item
            .map((x) =>
                x.tags ? x.tags.map((y) => y.tag_val_name_lang) : null
            )
            .join(",");
    };

    const transformSizeList = (item) => {
        return JSON.stringify(
            Object.values(item.sale_attr_list)[0].skc_sale_attr
        );
    };

    // for (let index = 0; index < sheinUrls.length; index++) {
    for (let index = 0; index < 1; index++) {
        const url = sheinUrls[index];
        const source = new URL(url).hostname;
        try {
            await page.goto(url);
            const productDetail = await page.evaluate(
                () => window.goodsDetailv2SsrData.productIntroData
            );

            const variants = productDetail.relation_color;

            let dataProduct = {
                url,
                status: "OK",
                source,
                sizeGuide: JSON.stringify(productDetail.detail.sizeTemplate),
                sizeGuideInfo: JSON.stringify(productDetail.sizeInfoDes),
                sizeList: transformSizeList(productDetail.attrSizeList),
                categories: transformCategories(productDetail.parentCats),
                tags: transformTags(productDetail.getSellingPoints),
                meta: JSON.stringify(productDetail.metaInfo),
            };

            data.push(
                Object.values({
                    ...dataProduct,
                    ...transformVariant(
                        productDetail.detail,
                        productDetail.goods_imgs
                    ),
                })
            );

            variants.forEach((variant) => {
                data.push(
                    Object.values({
                        ...dataProduct,
                        ...transformVariant(variant),
                    })
                );
            });

            console.log(index + 1 + "/" + sheinUrls.length + " - " + url);
        } catch (error) {
            console.log(error);
            const product = {
                url,
                status: "Error",
            };

            console.log(index + 1 + "/" + sheinUrls.length + " - " + url);

            data.push(Object.values(product));
        }

        if (index > 0 && index % 10 === 0) {
            exportFile(data);
        }
    }

    exportFile(data);
    await browser.close();
})();
