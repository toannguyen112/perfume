@
<template>
  <div
    v-show="show"
    class="fixed top-0 bottom-0 right-0 z-50 bg-white left-from-sidebar"
  >
    <div class="topbar">
      <h1 class="flex items-center font-semibold text-gray-700">
        <div
          v-if="selectable"
          class="p-4 -ml-4 cursor-pointer hover:text-gray-900"
          @click="$emit('update:show', false)"
        >
          <Icon name="arrow-right" class="transform rotate-180" />
        </div>
        Media Manager
      </h1>
      <div class="flex ml-auto space-x-3">
        <input
          type="text"
          placeholder="Nhập tên tệp..."
          class="
            flex-inline
            w-[400px]
            py-[0.5rem]
            px-[1rem]
            border border-gray-300
            focus:border-solid focus:outline-none focus:ring-0
            rounded
            hover:border-gray-400
            focus:border-gray-500
          "
          @input="onChange"
        />
        <Button class="space-x-2" @click.prevent="browse">
          <Icon name="upload" />
          <span> Upload </span>
        </Button>
        <input
          ref="image"
          type="file"
          class="hidden"
          accept="image/png, image/gif, image/jpeg, image/svg+xml, application/pdf"
          multiple="true"
          @change="fileChange"
        />
      </div>
    </div>
    <div class="flex">
      <div
        class="px-4 py-4 overflow-auto sm:px-10 md:px-12 sm:py-10 md:flex-1"
        @dragover.prevent="isDragging = true"
      >
        <div
          class="
            fixed
            inset-0
            border-4 border-dashed
            before:absolute
            before:bg-green-300
            before:bg-opacity-25
            before:inset-0
            before:z-10
          "
          :class="
            isDragging
              ? 'z-10 border-green-300 before:visible visible'
              : 'z-0 border-transparent before:invisible invisible'
          "
          @dragleave.prevent="isDragging = false"
          @drop.prevent="(isDragging = false), (dragCounter = 0), drop($event)"
        ></div>
        <div class="relative grid grid-cols-8 gap-4 pb-12">
          <div
            v-for="(file, index) in uploadingFiles.slice().reverse()"
            :key="index"
            class="relative col-span-1"
            @dragover.prevent="isDragging = true"
          >
            <div
              v-if="!file.id"
              class="
                absolute
                inset-0
                z-10
                flex
                items-center
                justify-center
                cursor-wait
              "
            >
              <i class="z-10 gg-spinner"></i>
              <div class="absolute inset-0 z-0 bg-white opacity-50"></div>
            </div>
            <div
              :class="
                file.path ? 'group cursor-pointer' : 'pointer-events-none '
              "
            >
              <div
                class="
                  overflow-hidden
                  transition-colors
                  duration-200
                  bg-gray-100
                  border border-gray-200
                  rounded
                  cursor-pointer
                  select-none
                  aspect-[1/1]
                  group-hover:border-gray-500
                  relation
                "
                :class="{
                  'outline-black': selectedFiles.includes(file),
                }"
                @click="onSelect(file)"
              >
                <img
                  class="object-contain pointer-events-none"
                  :src="file.base64_code"
                  loading="lazy"
                />
              </div>

              <Button
                v-show="!selectedFiles.includes(file)"
                size="xxs"
                class="
                  absolute
                  invisible
                  transition-all
                  duration-200
                  opacity-0
                  right-3
                  bottom-3
                "
                :class="{
                  'group-hover:opacity-100 group-hover:visible': file.path,
                }"
                @click="deleteFile(file)"
              >
                Xoá
              </Button>
            </div>
          </div>

          <div
            v-for="(file, index) in data.data"
            v-if="data"
            :key="index"
            class="relative col-span-1"
          >
            <div class="cursor-pointer group">
              <div
                class="
                  overflow-hidden
                  transition-colors
                  duration-200
                  bg-gray-100
                  border border-gray-200
                  rounded
                  select-none
                  aspect-square
                  group-hover:border-gray-500
                "
                :class="{
                  'outline outline-offset-2 outline-2 outline-blue-500':
                    selectedFiles.includes(file),
                }"
                @click="onSelect(file)"
              >
                <img
                  v-if="isImage(file.filename)"
                  class="object-contain pointer-events-none"
                  :src="file.static_url"
                  loading="lazy"
                />
                <div v-else class="flex items-center p-4 text-xs break-all">
                  {{ file.filename }}
                </div>
              </div>
              <Button
                v-show="!selectedFiles.includes(file)"
                size="xxs"
                class="
                  absolute
                  invisible
                  transition-all
                  duration-200
                  opacity-0
                  right-3
                  bottom-3
                  group-hover:opacity-100 group-hover:visible
                "
                @click="deleteFile(file)"
              >
                Xoá
              </Button>
            </div>
          </div>
        </div>

        <Pagination
          v-if="data"
          :links="data.links"
          @changePage="getMedias($event)"
        />
      </div>
    </div>
    <div
      v-if="multiple && selectedFiles.length > 0"
      class="
        absolute
        bottom-0
        left-0
        right-0
        flex
        items-center
        justify-center
        w-full
        h-16
        space-x-2
        bg-white
        border-t
      "
    >
      <Button @click="selectedFiles = []"> Bỏ chọn </Button>
      <Button @click="submitFileSelect()">
        Chọn ({{ selectedFiles.length }})
      </Button>
    </div>
  </div>
</template>
<script>
import { onMounted, onUnmounted } from "vue";

const MAX_SIZE_OF_IMAGE = 5;
const MAX_SIZE_OF_VIDEO = 10;

export default {
  props: {
    show: {
      default: false,
    },
    multiple: {
      default: false,
    },
    selectable: {
      default: true,
    },
  },

  setup(props, { emit }) {
    const close = () => {
      emit("update:show", false);
    };

    const closeOnEscape = (e) => {
      if (e.key === "Escape" && props.show) {
        close();
      }
    };

    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => {
      document.removeEventListener("keydown", closeOnEscape);
    });

    return {
      close,
    };
  },

  data() {
    return {
      uploadingFiles: [],
      selectedFiles: [],
      isDragging: false,
      data: null,
      timer: null,
    };
  },

  watch: {
    show(value) {
      if (value && this.data === null) {
        this.getMedias();
      }
    },
  },

  created() {
    this.getMedias();
  },

  methods: {
    getMedias(page = 1, search = "") {
      axios.get(route("sidebar.media.index", { page, search })).then((res) => {
        this.data = res.data;
      });
    },
    onSelect(file) {
      if (!this.selectable) return;

      if (!this.multiple) {
        this.selectedFiles[0] = file;
        this.submitFileSelect();
      } else {
        if (!this.selectedFiles.includes(file)) {
          this.selectedFiles.push(file);
        } else {
          const fileIndex = this.selectedFiles.indexOf(file);
          this.selectedFiles.splice(fileIndex, 1);
        }
      }
    },
    submitFileSelect() {
      this.$emit("on-select", this.selectedFiles);
      this.selectedFiles = [];
      this.$emit("update:show", false);
    },
    browse() {
      this.$refs.image.click();
    },
    drop(event) {
      this.uploadFiles(event.dataTransfer.files);
    },
    fileChange() {
      this.uploadFiles(this.$refs.image.files);
    },
    uploadFiles(images) {
      if (images.length === 0) return;

      for (const image of images) {
        const fileCheck = this.fileCheck(image);
        if (!fileCheck.valid) {
          alert(
            `Dung lượng file tối đa là ${fileCheck.maxSize}MB. Vui lòng thử lại.`
          );
          this.$refs.image.value = "";
          return false;
        }
      }

      var formData = new FormData();
      for (let index = 0; index < images.length; index++) {
        const image = images[index];

        if (this.isImage(image.name)) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.uploadingFiles.push({
              filename: image.name,
              base64_code: e.target.result,
            });
          };
          reader.readAsDataURL(image);
        } else {
          this.uploadingFiles.push({
            filename: image.name,
            base64_code: null,
            size: image.size,
          });
        }
        formData.append("files[" + index + "]", image);
      }
      this.$refs.image.value = "";
      this.postFiles(formData);
    },

    postFiles(formData) {
      axios.post(route("sidebar.media.store"), formData).then((response) => {
        if (response.status === 200) {
          for (let index = 0; index < response.data.data.length; index++) {
            const file = response.data.data[index];
            this.uploadingFiles[index] = {
              ...this.uploadingFiles[index],
              ...file,
            };
          }
        }
      });
    },
    deleteFile(file) {
      axios.post(route("sidebar.media.destroy", { id: file.id }));
      const fileIndex = this.data.data.findIndex((x) => x.id === file.id);
      this.data.data.splice(fileIndex, 1);

      const uploadingFileIndex = this.uploadingFiles.findIndex(
        (x) => x.id === file.id
      );
      this.uploadingFiles.splice(uploadingFileIndex, 1);
    },
    fileCheck(file) {
      const maxSize = this.isImage(file.filename)
        ? MAX_SIZE_OF_IMAGE
        : MAX_SIZE_OF_VIDEO;
      const fileSize = file.size / 1024 / 1024;

      return { valid: fileSize <= maxSize, maxSize };
    },
    onChange(event) {
      if (this.timer) {
        clearTimeout(this.timer);
        this.timer = null;
      }

      this.timer = setTimeout(() => {
        this.getMedias(1, event.target.value);
      }, 150);
    },
  },
};
</script>
<style lang="scss" scoped>
.left-from-sidebar {
  left: var(--sidebar-width);
}
.topbar {
  @apply absolute flex items-center flex-shrink-0 w-full px-4 bg-white border-b sm:px-10 md:px-12;
  height: var(--topbar-height);
}

.topbar + div {
  @apply fixed right-0;
  top: var(--topbar-height);
  height: calc(100% - var(--topbar-height));
  left: var(--sidebar-width);
}
</style>
