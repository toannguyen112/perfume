<template>
  <div class="w-max">
    <input
      type="file"
      class="hidden"
      :accept="typeConfig.accept"
      ref="image"
      @change="onFileChange"
      multiple
    />
    <div
      class="flex space-x-[10px] py-[16px] px-[24px] border border-solid border-gray-500 text-[16px] leading-[100%] text-gray-800 font-bold cursor-pointer"
      @click.prevent="browse"
    >
      <svg
        width="20"
        height="16"
        viewBox="0 0 20 16"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M13.8199 5.45455C13.8199 5.74387 13.7044 6.02135 13.4988 6.22593C13.2932 6.43052 13.0144 6.54545 12.7237 6.54545C12.433 6.54545 12.1542 6.43052 11.9486 6.22593C11.743 6.02135 11.6276 5.74387 11.6276 5.45455C11.6276 5.16522 11.743 4.88774 11.9486 4.68316C12.1542 4.47857 12.433 4.36364 12.7237 4.36364C13.0144 4.36364 13.2932 4.47857 13.4988 4.68316C13.7044 4.88774 13.8199 5.16522 13.8199 5.45455ZM19.666 1.45455V13.0909V14.5455C19.666 14.9312 19.512 15.3012 19.2379 15.574C18.9638 15.8468 18.5921 16 18.2045 16H2.12755C1.73993 16 1.36818 15.8468 1.09409 15.574C0.819999 15.3012 0.666016 14.9312 0.666016 14.5455V11.6364V1.45455C0.666016 1.06878 0.819999 0.698807 1.09409 0.426026C1.36818 0.153246 1.73993 0 2.12755 0H18.2045C18.5921 0 18.9638 0.153246 19.2379 0.426026C19.512 0.698807 19.666 1.06878 19.666 1.45455ZM18.2045 11.3364V1.45455H2.12755V9.88182L5.47996 6.54545C5.75511 6.27563 6.12589 6.12438 6.51217 6.12438C6.89845 6.12438 7.26923 6.27563 7.54438 6.54545L11.6276 10.6091L13.5184 8.72727C13.7936 8.45745 14.1643 8.3062 14.5506 8.3062C14.9369 8.3062 15.3077 8.45745 15.5828 8.72727L18.2045 11.3364Z"
          fill="black"
        />
      </svg>

      <div class="relative">
        <div>THÊM HÌNH ẢNH</div>
        <div
          v-if="images && images.length > 0"
          class="absolute text-[12px] leading-[100%] text-blue-400 font-light"
        >
          Đã thêm {{ images.length }} hình ảnh
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const MAX_FILE_SIZE = 5;

export default {
  props: ["field", "value", "modelValue"],
  emits: ["change", "update:modelValue"],

  data() {
    return {
      images: [],
    };
  },

  computed: {
    multiple() {
      return this.field.config && this.field.config.multiple
        ? this.field.config.multiple
        : false;
    },

    acceptType() {
      return this.field.config && this.field.config.accept
        ? this.field.config.accept
        : "image";
    },

    typeConfig() {
      return {
        image: {
          accept: "image/png, image/gif, image/jpeg",
          text: "Chọn hình ảnh",
          maxsize: 5,
        },
        video: {
          accept: "video/mp4,video/x-m4v,video/*",
          text: "Chọn video",
          maxsize: 100,
        },
        pdf: {
          accept: "application/pdf",
          text: "Chọn tệp",
          maxsize: 5,
        },
      }[this.acceptType];
    },
  },

  created() {
    if (!this.value || Object.keys(this.value).length === 0) return;
    if (Object.keys(this.value)[0] === "id") {
      this.images.push(this.value);
    } else {
      this.images = this.value;
    }
  },

  methods: {
    browse() {
      this.$refs.image.click();
    },

    onFileChange() {
      let images = this.$refs.image.files;
      if (images.length === 0) return;

      // this.images.splice(0, this.images.length);

      for (const image of images) {
        if (!this.filesizeValid(image)) {
          alert(
            `Dung lượng file tối đa là ${this.typeConfig.maxsize}MB. Vui lòng thử lại.`
          );
          this.$refs.image.value = "";
          return false;
        }
      }

      var formData = new FormData();
      for (let index = 0; index < images.length; index++) {
        const image = images[index];

        const reader = new FileReader();
        reader.onload = (e) => {
          this.images.push({
            id: null,
            obj: image,
            name: image.name,
            preview: e.target.result,
          });
        };
        reader.readAsDataURL(image);
        formData.append("files[" + index + "]", image);
      }
      this.image = images;
      this.$refs.image.value = "";
      this.uploadImages();
    },

    uploadImages() {
      this.emitImages();
    },

    removeImage(index) {
      this.images.splice(index, 1);
      this.emitImages();
    },

    filesizeValid(file) {
      const fileSize = file.size / 1024 / 1024;
      return fileSize <= this.typeConfig.maxsize;
    },

    emitImages() {
      this.$emit("update:modelValue", this.images);
      // this.$emit("change", this.images);
    },
  },
};
</script>
