<template>
  <app-layout>
    <template #header>Team Members</template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Team Members</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('members.create')">
        <primary-button>Add Member</primary-button>
      </inertia-link>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">All</div>
          </div>
          <div class="page-section-content">
            <div v-if="members.length === 0" class="text-center text-gray-400 text-sm py-8">
              No team members yet.
            </div>
            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div v-for="member in members" :key="member.id" class="card p-4 flex gap-4 items-start">
                <img :src="'/' + member.photo" :alt="member.name" class="h-16 w-16 rounded-full object-cover flex-shrink-0">
                <div class="min-w-0 flex-1">
                  <p class="font-semibold text-gray-800 dark:text-white truncate">{{ member.name }}</p>
                  <p class="text-sm text-gray-500 dark:text-gray-400">{{ member.position }}</p>
                  <div class="mt-3 flex gap-3">
                    <inertia-link :href="route('members.edit', { id: member.id })" class="text-xs text-blue-600 hover:underline">Edit</inertia-link>
                    <button @click="confirmDelete(member)" class="text-xs text-red-500 hover:underline">Delete</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <jet-dialog-modal :show="deleting !== null" @close="deleting = null">
      <template #title>Delete Team Member</template>
      <template #content>
        Are you sure you want to remove <strong>{{ deleting?.name }}</strong>? This cannot be undone.
      </template>
      <template #footer>
        <jet-secondary-button @click="deleting = null">Cancel</jet-secondary-button>
        <jet-danger-button class="ml-2" :class="{ 'opacity-25': deleteForm.processing }" :disabled="deleteForm.processing" @click="deleteMember">
          Delete
        </jet-danger-button>
      </template>
    </jet-dialog-modal>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetDangerButton from '@/Jetstream/DangerButton'

export default {
  components: { AppLayout, PrimaryButton, JetDialogModal, JetSecondaryButton, JetDangerButton },
  props: ['members'],
  data() {
    return {
      deleting: null,
      deleteForm: this.$inertia.form({}),
    }
  },
  methods: {
    confirmDelete(member) {
      this.deleting = member
    },
    deleteMember() {
      this.deleteForm.delete(this.route('members.destroy', { id: this.deleting.id }), {
        preserveScroll: true,
        onSuccess: () => { this.deleting = null },
      })
    },
  },
}
</script>
