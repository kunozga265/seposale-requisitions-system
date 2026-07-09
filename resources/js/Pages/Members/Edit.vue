<template>
  <app-layout>
    <template #header>Edit Team Member</template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
          <a :href="route('members.index')" class="heading-font uppercase text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Team Members</a>
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Edit</span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <form @submit.prevent="submit" enctype="multipart/form-data">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">Profile</div>
            </div>
            <div class="page-section-content flex justify-center">
              <div class="card w-full sm:max-w-md md:max-w-3xl">
                <jet-validation-errors class="mb-4 p-2" />

                <div class="p-2 mb-2">
                  <jet-label for="name" value="Full Name" />
                  <jet-input id="name" type="text" class="block w-full" v-model="form.name" />
                </div>

                <div class="p-2 mb-2">
                  <jet-label for="position" value="Position / Title" />
                  <jet-input id="position" type="text" class="block w-full" v-model="form.position" />
                </div>

                <div class="p-2 mb-2">
                  <jet-label value="Current Photo" />
                  <img :src="photoPreview || ('/' + member.photo)" class="mt-2 h-24 w-24 rounded-full object-cover" />
                  <div class="mt-3">
                    <jet-label for="photo" value="Replace Photo" />
                    <input id="photo" type="file" accept="image/*" class="block w-full text-sm text-gray-500 mt-1" @change="onPhoto" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">Bio</div>
            </div>
            <div class="page-section-content flex justify-center">
              <div class="w-full sm:max-w-md md:max-w-3xl">
                <vue2-tinymce-editor v-model="form.body" />
              </div>
            </div>
          </div>

          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">Social Links</div>
            </div>
            <div class="page-section-content flex justify-center">
              <div class="card w-full sm:max-w-md md:max-w-3xl">
                <div class="p-2 mb-2">
                  <jet-label for="facebook" value="Facebook URL" />
                  <jet-input id="facebook" type="url" class="block w-full" v-model="form.facebook" placeholder="https://facebook.com/..." />
                </div>
                <div class="p-2 mb-2">
                  <jet-label for="twitter" value="Twitter / X URL" />
                  <jet-input id="twitter" type="url" class="block w-full" v-model="form.twitter" placeholder="https://x.com/..." />
                </div>
                <div class="p-2 mb-2">
                  <jet-label for="linkedin" value="LinkedIn URL" />
                  <jet-input id="linkedin" type="url" class="block w-full" v-model="form.linkedin" placeholder="https://linkedin.com/in/..." />
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 pb-8">
            <inertia-link :href="route('members.index')" class="mr-4 inline-flex items-center px-4 py-2 text-sm text-gray-600 hover:text-gray-900">
              Cancel
            </inertia-link>
            <primary-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
              Save Changes
            </primary-button>
          </div>
        </form>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from '@/Jetstream/Button'
import JetLabel from '@/Jetstream/Label'
import JetInput from '@/Jetstream/Input'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default {
  components: { AppLayout, PrimaryButton, JetLabel, JetInput, JetValidationErrors },
  props: ['member', 'links'],
  data() {
    return {
      photoPreview: null,
      form: this.$inertia.form({
        name:     this.member.name,
        position: this.member.position,
        body:     this.member.body,
        photo:    null,
        facebook: this.links?.facebook ?? '',
        twitter:  this.links?.twitter ?? '',
        linkedin: this.links?.linkedin ?? '',
      }),
    }
  },
  methods: {
    onPhoto(e) {
      const file = e.target.files[0]
      if (!file) return
      this.form.photo = file
      const reader = new FileReader()
      reader.onload = (ev) => { this.photoPreview = ev.target.result }
      reader.readAsDataURL(file)
    },
    submit() {
      this.form.post(this.route('members.update', { id: this.member.id }), {
        forceFormData: true,
      })
    },
  },
}
</script>
