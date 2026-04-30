word<template>
  <app-layout>
    <template #header>
      Templates
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <a :href="route('whatsapp.index')"
            class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
            Whatsapp Messages
          </a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Templates</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('whatsapp.templates.send')">
        <primary-button>
          Send Messages
        </primary-button>
      </inertia-link>


      <secondary-button @click.native="addDialog = true">
        Add
      </secondary-button>

      <secondary-button @click.native="editDialog = true">
        Edit
      </secondary-button>
    </template>


    <dialog-modal :show="addDialog" @close="addDialog = false">
      <template #title>
        Add Template
      </template>

      <template #content>
        <jet-validation-errors class="mb-4" />

        <div class="mb-4">
          <div v-show="!validation" class="flex items-center w-full text-red">
            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ error }}</div>
          </div>
        </div>


        <div class="p-2 mb-2">
          <jet-label for="name" value=" Name" />
          <jet-input id="name" type="text" class="block w-full" v-model="form.name" placeholder=""
            autocomplete="seposale-template-name" />
        </div>
        <div class="p-2 mb-2">
          <jet-label for="code" value=" Code" />
          <jet-input id="code" type="text" class="block w-full" v-model="form.code" placeholder=""
            autocomplete="seposale-template-code" />
        </div>
        <div class="p-2 mb-2">
          <jet-label for="description" value=" Description" />
          <vue2-tinymce-editor v-model="form.description"></vue2-tinymce-editor>
          <!-- <jet-input id="description" type="text" class="block w-full" v-model="form.description" placeholder=""
            autocomplete="seposale-template-description" /> -->
        </div>


        <div class="flex items-center mb-2 md:col-span-2">
          <input checked id="hasFile" type="checkbox" value="" v-model="form.hasFile"
            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="hasFile" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Has File</label>
        </div>
      </template>

      <template #footer>
        <secondary-button @click.native="addDialog = false">
          Cancel
        </secondary-button>

        <primary-button v-show="validation" class="ml-2" @click.native="addTemplate" :disabled="form.processing">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Proceed
        </primary-button>
      </template>
    </dialog-modal>

    <dialog-modal :show="editDialog" @close="editDialog = false">
      <template #title>
        Edit Template
      </template>

      <template #content>
        <jet-validation-errors class="mb-4" />

        <div class="mb-4">
          <div v-show="!updateValidation" class="flex items-center w-full text-red">
            <div class="text-sm text-red"><i class="mdi mdi-alert-circle text-red"></i> {{ updateError }}</div>
          </div>
        </div>


        <div class="p-2 mb-2 md:col-span-2">
          <jet-label for="template" value="Template" />
          <select v-model="templateIndex"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
            <option :value="index" v-for="(template, index) in templates" :key="index">
              {{ template.name }}
            </option>
          </select>
        </div>

        <div v-if="templateIndex >= 0">

          <div class="p-2 mb-2">
            <jet-label for="name" value=" Name" />
            <jet-input id="name" type="text" class="block w-full" v-model="form.name" placeholder=""
              autocomplete="seposale-template-name" />
          </div>
          <div class="p-2 mb-2">
            <jet-label for="code" value=" Code" />
            <jet-input id="code" type="text" class="block w-full" v-model="form.code" placeholder=""
              autocomplete="seposale-template-code" />
          </div>
          <div class="p-2 mb-2">
            <jet-label for="description" value=" Description" />
            <vue2-tinymce-editor v-model="form.description"></vue2-tinymce-editor>
            <!-- <jet-input id="description" type="text" class="block w-full" v-model="form.description" placeholder=""
            autocomplete="seposale-template-description" /> -->
          </div>


          <div class="flex items-center mb-2 md:col-span-2">
            <input checked id="hasFile" type="checkbox" value="" v-model="form.hasFile"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="hasFile" class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-300">Has File</label>
          </div>

        </div>



      </template>

      <template #footer>
        <secondary-button @click.native="editDialog = false">
          Cancel
        </secondary-button>

        <primary-button v-show="updateValidation" class="ml-2" @click.native="editTemplate" :disabled="form.processing">
          <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin"
            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
              d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
              fill="#E5E7EB" />
            <path
              d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
              fill="currentColor" />
          </svg>
          Proceed
        </primary-button>
      </template>
    </dialog-modal>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="templates.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Templates Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">

                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Name</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Code</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Description</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Has File</th>


                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr class="border-b cursor-pointer hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(template, index) in templates" :key="index">
                        <td class="p-2 text-left ">{{ index + 1 }}. {{ template.name }}</td>
                        <td class="p-2 text-left ">{{ template.code }}</td>
                        <td class="p-2 text-left " v-html="template.description"></td>
                        <td class="p-2 text-left ">{{ template.has_file ? 'Yes' : 'No' }}</td>

                      </tr>
                    </tbody>
                  </table>
                </div>

              </div>
              <!--              <pagination :object="templates"/>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import DoughnutChart from "@/Components/Charts/DoughnutChart";
import PieChart from "@/Components/Charts/PieChart";
import Request from "@/Components/Request";
import PrimaryButton from "@/Jetstream/Button";
import RequestStatus from "@/Components/RequestStatus.vue";
import Pagination from "@/Components/Pagination.vue";
import SaleStatus from "@/Components/SaleStatus.vue";
import DeliveryStatus from "@/Components/DeliveryStatus.vue";
import JetInput from "@/Jetstream/Input.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import DialogModal from "@/Jetstream/DialogModal.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";
import { Vue2TinymceEditor } from "vue2-tinymce-editor";

export default {
  props: [
    'templates',
  ],
  components: {
    JetValidationErrors,
    JetLabel,
    DialogModal, SecondaryButton,
    JetInput, DeliveryStatus, SaleStatus,
    Pagination,
    RequestStatus,
    AppLayout,
    PrimaryButton,
    Vue2TinymceEditor,
  },
  data() {
    return {
      addDialog: false,
      editDialog: false,
      // sendDialog: false,
      updateError: "",
      templateIndex: -1,
      variantCost: 0,
      variantCostOriginal: 0,
      form: this.$inertia.form({
        name: '',
        code: '',
        description: '',
        hasFile: false
      }),

    }
  },
  computed: {

    validation() {
      if (this.form.name.length === 0) {
        this.error = "Enter name of template"
        return false
      } else if (this.form.code.length === 0) {
        this.error = "Enter code of template"
        return false
      } else
        return true

    },
    updateValidation() {
      if (this.templateIndex < 0) {
        this.updateError = "Select template"
        return false
      } else if (this.form.name.length === 0) {
        this.updateError = "Enter name of template"
        return false
      } else if (this.form.code.length === 0) {
        this.updateError = "Enter code of template"
        return false
      } else
        return true

    },
  },
  watch: {
    templateIndex() {
      if (this.templateIndex < 0) {
        this.form.name = ""
        this.form.code = ""
        this.form.description = ""
        this.form.hasFile = false
      } else {
        this.form.name = this.templates[this.templateIndex].name
        this.form.code = this.templates[this.templateIndex].code
        this.form.description = this.templates[this.templateIndex].description
        this.form.hasFile = this.templates[this.templateIndex].has_file
      }
    },
    addDialog() {
      this.templateIndex = -1
      this.form.name = ""
      this.form.code = ""
      this.form.description = ""
      this.form.hasFile = false
    },
    editDialog() {
      this.templateIndex = -1
      this.form.name = ""
      this.form.code = ""
      this.form.description = ""
      this.form.hasFile = false
    },
    // sendDialog() {
    //   this.templateIndex = -1
    //   this.form.name = ""
    //   this.form.code = ""
    //   this.form.description = ""
    //   this.form.hasFile = false
    // }
  },
  methods: {
    // navigateToTemplate(id) {
    //   this.$inertia.get(this.route('templates.show', { 'id': id }))
    // },

    addTemplate() {
      this.form
        .transform(data => ({
          ...data,
          has_file: this.form.hasFile,
        }))
        .post(this.route('whatsapp.templates.store'), {
          onSuccess: () => this.addDialog = false,
        })
    },
    editTemplate() {
      const id = this.templates[this.templateIndex].id
      this.form
        .transform(data => ({
          ...data,
          id: id,
          has_file: this.form.hasFile,
        }))
        .post(this.route('whatsapp.templates.update', { "id": id }), {
          onSuccess: () => {
            this.editDialog = false
            this.templateIndex = -1
          },
        })
    },

  }
}
</script>
