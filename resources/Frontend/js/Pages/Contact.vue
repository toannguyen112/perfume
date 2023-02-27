<template>
  <section>
    <div class="h-[480px] xl:max-h-[100vh] max-h-[40vh]">
      <JPicture
        src="/images/contact/banner.jpg"
        class="object-cover w-full h-full"
        alt="banner"
      />
    </div>
  </section>
  <section class="xl:mt-[48px] md:mt-[34px] mt-[24px]">
    <div class="container">
      <h1
        class="h2 font-bold text-black xl:mb-[48px] md:mb-[34px] mb-[24px] text-center"
      >
        LIÊN HỆ VỚI CHÚNG TÔI
      </h1>
    </div>
    <div class="relative">
      <div class="lg:h-[720px] md:h-[500px] h-[360px] w-full relative">
        <GoogleMap
          ref="mapRef"
          api-key="AIzaSyDZLRQYytLIIumwSv7CpQswCzkILRH9acM"
          style="width: 100%; height: 100%"
          :center="{ lat: 10.802388708419402, lng: 106.68230706826093 }"
          :zoom="17"
          :options="options"
          class="block map-responsive"
        >
          <Marker
            :options="{
              position: {
                lat: 10.802388708419402,
                lng: 106.68230706826093,
              },
            }"
            :icon="{ url: '../../svg/map.svg' }"
            :radius="1000"
            :visible="true"
          />
        </GoogleMap>
      </div>
      <div
        class="bg-black xl:p-[56px] md:p-[39px] p-[28px] lg:absolute top-0 lg:left-[5%] lg:max-w-[467px]"
      >
        <div class="xl:mb-[24px] md:mb-[17px] mb-[12px]">
          <JIcon name="logo" class="text-white" />
        </div>
        <div>
          <div
            class="xl:pb-[24px] md:pb-[17px] pb-[12px] border-b border-dashed border-gray-100 space-y-[12px]"
          >
            <div
              class="flex space-between xl:space-x-[32px] md:space-x-[22px] space-x-[16px]"
            >
              <div class="flex-shrink-0 font-medium text-gray-400 w-[56px]">
                Địa chỉ
              </div>
              <a
                href="https://goo.gl/maps/TLXhG7RNyNSV8YXA9"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-block font-bold text-white duration-150 hover:text-red-500"
                >29 Bis Phan Xích Long, Phường 3, Quận Phú Nhuận, TP.HCM</a
              >
            </div>
            <div
              class="flex space-between xl:space-x-[32px] md:space-x-[22px] space-x-[16px]"
            >
              <div class="flex-shrink-0 font-medium text-gray-400 w-[56px]">
                Email
              </div>
              <a
                href="mailto:emdepemxinh9999@gmail.com."
                class="inline-block font-bold text-white duration-150 hover:text-red-500"
                >emdepemxinh9999@gmail.com</a
              >
            </div>
            <div
              class="flex space-between xl:space-x-[32px] md:space-x-[22px] space-x-[16px]"
            >
              <div class="flex-shrink-0 font-medium text-gray-400 w-[56px]">
                Hotline
              </div>
              <a
                href="tel:+0981064444"
                class="inline-block font-bold text-white duration-150 hover:text-red-500"
                >098.106.4444</a
              >
            </div>
          </div>
          <div class="xl:pt-[24px] md:pt-[17px] pt-[12px]">
            <div class="font-medium text-gray-400 mb-[12px]">
              Thời gian mở cửa
            </div>
            <div class="font-bold text-white">
              08:00 - 21:30, từ thứ 2 đến Chủ Nhật
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section
    class="xl:mt-[114px] lg:mt-[80px] xl:pt-[32px] md:pt-[22px] pt-[16px] xl:pb-[60px] md:pb-[42px] pb-[30px] border-t border-gray-300"
  >
    <div class="container">
      <div class="xl:mb-[56px] md:mb-[39px] mb-[28px]">
        <JIcon name="bg-contact" />
      </div>
      <div class="grid grid-cols-12 xl:gap-x-[32px] md:gap-x-[22px]">
        <div
          class="xl:col-span-7 lg:col-span-6 lg:block hidden xl:pr-[37px] lg:pr-[24px]"
        >
          <JPicture
            src="/images/contact/img-left.jpg"
            class="object-cover w-full h-full"
            alt="img-left"
          />
        </div>
        <div class="xl:col-span-5 lg:col-span-6 col-span-full">
          <div class="xl:mb-[32px] md:mb-[22px] mb-[16px]">
            <div
              class="h2 font-bold text-black xl:mb-[16px] md:mb-[12px] mb-[8px]"
            >
              BẠN CẦN TRAO ĐỔI <br />
              VỚI CHÚNG TÔI
            </div>
            <div class="font-medium text-gray-800">
              Hãy để lại thông tin, chúng tôi sẽ phản hồi trong thời gian sớm
              nhất.
            </div>
          </div>
          <div>
            <div class="xl:mb-[32px] md:mb-[22px] mb-[16px] space-y-[28px]">
              <div>
                <JFieldSet
                  v-model="form.contact.data['Họ và tên']"
                  :field="{
                    rules: rules,
                    errors: errors,
                    type: 'text',
                    placeholder: 'Nhập họ và tên',
                    name: 'Họ và tên',
                    fieldName: 'Họ và tên',
                    label: 'Họ và tên',
                    isSubmit: isSubmit,
                  }"
                  :contactForm="false"
                />
              </div>
              <div>
                <JFieldSet
                  v-model="form.contact.data['Số điện thoại']"
                  :field="{
                    rules: rules,
                    errors: errors,
                    type: 'number',
                    placeholder: 'Nhập số điện thoại',
                    label: 'Số điện thoại',
                    fieldName: 'Số điện thoại',
                    name: 'Số điện thoại',
                    isSubmit: isSubmit,
                  }"
                  :contactForm="false"
                />
              </div>
              <div>
                <JFieldSet
                  v-model="form.contact.data['Email']"
                  :field="{
                    rules: rules,
                    errors: errors,
                    type: 'email',
                    placeholder: 'Nhập email',
                    fieldName: 'Email',
                    label: 'Email',
                    name: 'Email',
                    isSubmit: isSubmit,
                  }"
                  :contactForm="false"
                />
              </div>
              <div>
                <JFieldSet
                  v-model="form.contact.data['Lời nhắn']"
                  :field="{
                    type: 'textarea',
                    placeholder: 'Nhập lời nhắn',
                    label: 'Lời nhắn',
                    fieldName: 'Lời nhắn',
                    name: 'Lời nhắn',
                    rules: rules,
                    errors: errors,
                  }"
                  :contactForm="false"
                  :isAddress="false"
                />
              </div>
            </div>
            <div>
              <JButton
                v-if="isSubmit === false"
                :loading="isLoading"
                label="HỖ TRỢ NGAY"
                variant="primary-square"
                @click="submit"
              />
              <div class="text-red-500 caption" v-else>
                Cảm ơn Quý khách đã liên hệ. Muội Muội sẽ liên lạc lại trong
                thời gian sớm nhất
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <SocialMeta
    title="Muội Muội - Liên hệ với chúng tôi"
    description="Quý khách cần khiếu nại hoặc góp ý xin vui lòng liên hệ với Muội Muội. Những ý kiến đóng góp của quý khách sẽ góp phần nâng cao chất lượng dịch vụ của chúng tôi."
  />
</template>

<script>
import App from "../Layouts/App.vue";
import { GoogleMap, Marker } from "vue3-google-map";
import { validateForm } from "../validator";

const emptyForm = {
  "Họ và tên": null,
  Email: null,
  "Số điện thoại": null,
  "Lời nhắn": null,
};

export default {
  components: {
    GoogleMap,
    Marker,
  },
  layout: App,
  data() {
    return {
      options: {
        styles: [
          {
            elementType: "geometry",
            stylers: [
              {
                color: "#f5f5f5",
              },
            ],
          },
          {
            elementType: "labels.icon",
            stylers: [
              {
                visibility: "off",
              },
            ],
          },
          {
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#616161",
              },
            ],
          },
          {
            elementType: "labels.text.stroke",
            stylers: [
              {
                color: "#f5f5f5",
              },
            ],
          },
          {
            featureType: "administrative.land_parcel",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#bdbdbd",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "geometry",
            stylers: [
              {
                color: "#eeeeee",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#757575",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [
              {
                color: "#e5e5e5",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#9e9e9e",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "geometry",
            stylers: [
              {
                color: "#ffffff",
              },
            ],
          },
          {
            featureType: "road.arterial",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#757575",
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [
              {
                color: "#dadada",
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#616161",
              },
            ],
          },
          {
            featureType: "road.local",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#9e9e9e",
              },
            ],
          },
          {
            featureType: "transit.line",
            elementType: "geometry",
            stylers: [
              {
                color: "#e5e5e5",
              },
            ],
          },
          {
            featureType: "transit.station",
            elementType: "geometry",
            stylers: [
              {
                color: "#eeeeee",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [
              {
                color: "#c9c9c9",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#9e9e9e",
              },
            ],
          },
        ],
      },
      form: this.$inertia.form({
        contact: {
          data: {
            ...emptyForm,
          },
          type: "CONTACT_FORM",
        },
      }),
      rules: {
        "Họ và tên": "required",
        Email: "required|email",
        "Số điện thoại": "required|phone",
        "Lời nhắn": "",
      },
      isLoading: false,
      processing: false,
      isSubmit: false,
      errors: {},
    };
  },
  methods: {
    submit() {
      this.errors = validateForm(this.form.contact.data, this.rules);
      if (Object.keys(this.errors).length > 0 || this.isLoading) {
        return false;
      }
      this.isLoading = true;
      this.form.post(this.route("contact.store"), {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset("contact"), (this.isSuccess = true);
        },
      });
      this.isSubmit = true;
      this.isLoading = false;
    },
  },
};
</script>
