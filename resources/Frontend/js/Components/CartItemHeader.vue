<template>
  <Link
    href="/"
    class="
      flex
      min-w-[260px]
      sm:min-w-[377px]
      hover:bg-gray-100
      transition-all
      ease-in-out
      duration-200
      bg-white
      mb-4
    "
  >
    <div class="w-[70px] h-[70px] relative">
      <JPicture
        src="https://via.placeholder.com/70"
        class="absolute inset-0 object-cover w-full h-full"
      />
    </div>
    <div class="flex-1 ml-1 mr-4">
      <span class="mb-1 font-medium text-gray-800 caption line-clamp-2">
        {{ item.name }}
      </span>
      <span class="font-medium text-gray-500 caption"
        >SL x {{ item.qty }}
      </span>
    </div>
    <strong class="ml-auto text-black caption"> {{ item.price }} </strong>
  </Link>
</template>

<script>
export default {
  props: ["item"],
};
</script>
