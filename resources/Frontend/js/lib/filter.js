"use strict";

function serializeQuery(oldQuery = {}, newQuery = {}) {
    return { ...serializeOldQuery(oldQuery), ...serializeNewQuery(newQuery) };
}

function serializeOldQuery(query = {}) {
    return Object.fromEntries(
        Object.entries(query).filter(([key]) => !key.includes("opt-"))
    );
}

function serializeNewQuery(query = {}) {
    return {
        ...serializeOptions(query),
        ...Object.fromEntries(
            Object.entries(query).filter(([key, x]) => typeof x === "string")
        ),
    };
}

function serializeOptions(options) {
    let queryOptions = {};
    for (const option of options) {
        const childIds = option.children
            .filter((c) => c.active)
            .map((x) => x.id);
        if (childIds.length > 0) {
            queryOptions[`opt-${option.slug}`] = childIds.join(",");
        }
    }

    return queryOptions;
}

function unserializeOptions(value) {
    const options = Object.keys(value)
        .filter((x) => x.includes("opt-"))
        .map((key) => {
            const newKey = key.replace("opt-", "");
            return { parent: newKey, child: value[key].split(",") };
        });
    return unGroupBy(options);
}

function groupBy(xs, f) {
    return xs.reduce(
        (r, v, i, a, k = f(v)) => ((r[k] || (r[k] = [])).push(v), r),
        {}
    );
}

function unGroupBy(value) {
    let data = [];
    for (const option of value) {
        for (const child of option.child) {
            data.push({
                parent: option.parent,
                child: child,
            });
        }
    }
    return data;
}

function filteringOptionIds(value) {
    return unserializeOptions(value).flatMap((x) => x.child.toString());
}

function mappingPrice(query) {
    const price = query.price ? query.price.split("-") : null;
    const from = price && price[0] ? price[0] : null;
    const to = price && price[1] ? price[1] : null;
    return { from, to };
}

function mappingOptions(allOptions, query) {
    const filteringIds = unserializeOptions(query).flatMap((x) =>
        x.child.toString()
    );
    return allOptions.map((x) => {
        x.children.map((c) => {
            c.active = !!filteringIds.includes(c.id.toString());
            return c;
        });
        return x;
    });
}

function convertOptionsTags(allOptions, query) {
    const filteringIds = unserializeOptions(query).flatMap((x) =>
        x.child.toString()
    );
    let tags = [];
    allOptions.forEach((option) => {
        option.children.forEach((child) => {
            if (filteringIds.includes(child.id.toString())) {
                tags.push({ ...child, parent_slug: option.slug });
            }
        });
    });

    return tags;
}

function convertOptionsTagsSlug(allOptions, query) {
    let tags = [];
    allOptions.forEach((option) => {
        option.children.forEach((child) => {
            if (query.includes(child.slug)) {
                tags.push({ ...child, parent_slug: option.slug });
            }
        });
    });

    return tags;
}

function removeOptionsTag(tag, query) {
    let queryArr = Object.entries(query);

    queryArr.map((x) => {
        if (x[0] === `opt-${tag.parent_slug}`) {
            let value = x[1].split(",");
            value = value.filter(x => x !== tag.id.toString());
            if (value.length > 0) {
                x[1] = value.join(',')
            } else {
                x[1] = null
            }
        }
        return x;
    });
    let queryObj = Object.fromEntries(queryArr);

    return queryObj;
}

module.exports = {
    serializeQuery,
    serializeOptions,
    unserializeOptions,
    filteringOptionIds,
    mappingOptions,
    convertOptionsTags,
    convertOptionsTagsSlug,
    mappingPrice,
    removeOptionsTag,
};
