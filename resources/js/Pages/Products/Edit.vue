<template>
  <app-layout>
    <template #header>
      Edit Product
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
               xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"></path>
          </svg>
          <a :href="route('products.index')" class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Products
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
                        #{{ product.data.name }}
                    </span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <form @submit.prevent="submit">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Details
              </div>
            </div>
            <div class="page-section-content flex justify-center">

              <div class="card w-full sm:max-w-md md:max-w-3xl">

                <jet-validation-errors class="mb-4"/>

                <div class="grid grid-cols-1 md:grid-cols-2">

                  <div class="p-2 mb-2 md:col-span-2">
                    <jet-label for="name" value="Product Name"/>
                    <jet-input id="name" type="text" class="block w-full"
                               v-model="form.name" placeholder="e.g. Quarry Stone"
                               autocomplete="seposale-product-name"/>
                  </div>

                  <div class="p-2 mb-2 md:col-span-2">
                    <jet-label for="description" value="Short Description"/>
                    <jet-input id="description" type="text" class="block w-full"
                               v-model="form.description" placeholder="Short summary shown in listings"
                               autocomplete="seposale-product-description"/>
                  </div>

                  <div class="p-2 mb-2 md:col-span-2">
                    <jet-label for="description_full" value="Full Description"/>
                    <textarea id="description_full" rows="5" v-model="form.description_full"
                               placeholder="Detailed description shown on the product page"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                  </div>

                  <div class="mb-4 md:col-span-2">
                    <jet-label value="Photo"/>
                    <img v-if="form.photo" :src="form.photo" class="h-24 w-24 object-cover rounded-md mb-2" alt="">
                    <input type="file" id="photo" @input="photoUpload($event.target.files[0])"
                      accept="image/*" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>
                    <div class="text-red-500 text-xs" v-if="form.errors.photo">{{ form.errors.photo }}</div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="fixed right-6 bottom-6 md:right-10 md:bottom-10">
            <div v-show="!validation" id="toast-danger"
                 class="flex items-center w-full max-w-xs p-4 mb-4 text-red-700 bg-red-100 rounded-lg shadow dark:text-red-400 dark:bg-red-800"
                 role="alert">
              <div
                  class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
                <i class="mdi mdi-alert-circle text-2xl"></i>
              </div>
              <div class="ml-3 text-sm font-normal">{{ error }}</div>
            </div>
          </div>


          <div class="text-center mt-8">
            <div v-show="validation">
              <jet-button class="ml-4 text-center" :class="{ 'opacity-25': form.processing }"
                          :disabled="form.processing">
                Update
              </jet-button>
              <div class="text-gray-600 text-sm">Please confirm all details before submission</div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import JetButton from "@/Jetstream/Button";
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import SecondaryButton from '@/Jetstream/SecondaryButton'

export default {
  props: ["product"],
  components: {
    AppLayout,
    JetInput,
    JetLabel,
    JetButton,
    JetValidationErrors,
    SecondaryButton,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.product.data.name,
        description: this.product.data.description,
        description_full: this.product.data.descriptionFull,
        photo: this.product.data.photo,
      }),
      error: '',
    }
  },
  computed: {
      validation() {
          if (this.form.name.length === 0) {
              this.error = "Enter product name"
              return false
          } else
              return true

      },
  },
  methods: {
    submit() {
      this.form.post(this.route('products.update', { id: this.product.data.id }))
    },

    photoUpload(file) {
      const reader = new FileReader();
      if (file) {
        reader.readAsDataURL(file);
        reader.onload = (e) => {
          axios.post(this.$page.props.publicPath + "api/1.0.0/upload", {
            type: "PRODUCTS",
            file: e.target.result
          }).then(res => {
            this.form.photo = res.data.file
          }).catch(function (res) {
            // this.form.errors.push(res.data.message)
          })
        };
      }
    },
  }

}
</script>
