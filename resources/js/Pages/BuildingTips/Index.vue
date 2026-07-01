<template>
  <app-layout>
    <template #header>
      Building Tips
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Building Tips</span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">

        <!-- Action bar -->
        <div class="flex items-center justify-between mb-4">
          <input v-model="search" type="text" placeholder="Search tips..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
          <jet-button @click="openAdd">Add Tip</jet-button>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="tip in filtered" :key="tip.id"
            class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
            <img v-if="tip.photo" :src="tip.photo" :alt="tip.title"
              class="w-full h-40 object-cover" />
            <div class="p-4 flex-1 flex flex-col">
              <div class="flex items-start justify-between mb-1">
                <h3 class="font-semibold text-gray-800 text-sm">{{ tip.title }}</h3>
                <span v-if="tip.category"
                  class="ml-2 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full whitespace-nowrap">
                  {{ tip.category }}
                </span>
              </div>
              <p class="text-gray-500 text-xs flex-1 line-clamp-3">{{ tip.body.substring(0, 120) }}…</p>
              <div class="flex items-center justify-between mt-3">
                <button @click="toggleActive(tip)"
                  :class="tip.active ? 'bg-green-500' : 'bg-gray-300'"
                  class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
                  <span :class="tip.active ? 'translate-x-5' : 'translate-x-1'"
                    class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform"></span>
                </button>
                <div class="flex gap-2">
                  <button @click="openEdit(tip)" class="text-indigo-600 hover:underline text-xs">Edit</button>
                  <button @click="confirmDelete(tip)" class="text-red-500 hover:underline text-xs">Delete</button>
                </div>
              </div>
            </div>
          </div>
          <div v-if="filtered.length === 0"
            class="col-span-3 text-center py-12 text-gray-400 bg-white shadow rounded-lg">
            No building tips found.
          </div>
        </div>

        <!-- Add/Edit Dialog -->
        <dialog-modal :show="showDialog" @close="closeDialog">
          <template #title>{{ editing ? 'Edit Tip' : 'Add Building Tip' }}</template>
          <template #content>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input v-model="form.title" type="text"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                <textarea v-model="form.body" rows="5"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"></textarea>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                  <input v-model="form.category" type="text"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Photo URL</label>
                  <input v-model="form.photo" type="text" placeholder="https://..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                </div>
              </div>
              <div class="flex items-center gap-2">
                <input v-model="form.active" type="checkbox" id="tip-active" class="rounded" />
                <label for="tip-active" class="text-sm text-gray-700">Active</label>
              </div>
            </div>
          </template>
          <template #footer>
            <jet-secondary-button @click="closeDialog">Cancel</jet-secondary-button>
            <jet-button class="ml-2" @click="save" :disabled="!form.title || !form.body">
              {{ editing ? 'Update' : 'Add' }}
            </jet-button>
          </template>
        </dialog-modal>

        <!-- Delete confirm -->
        <confirmation-modal :show="showDelete" @close="showDelete = false">
          <template #title>Delete Tip</template>
          <template #content>Are you sure you want to delete this building tip?</template>
          <template #footer>
            <jet-secondary-button @click="showDelete = false">Cancel</jet-secondary-button>
            <jet-danger-button class="ml-2" @click="doDelete">Delete</jet-danger-button>
          </template>
        </confirmation-modal>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetDangerButton from '@/Jetstream/DangerButton.vue';
import DialogModal from '@/Jetstream/DialogModal.vue';
import ConfirmationModal from '@/Jetstream/ConfirmationModal.vue';

export default {
  components: { AppLayout, JetButton, JetSecondaryButton, JetDangerButton, DialogModal, ConfirmationModal },

  props: {
    tips: { type: Array, default: () => [] },
  },

  data() {
    return {
      search: '',
      showDialog: false,
      showDelete: false,
      editing: null,
      deleting: null,
      form: { title: '', body: '', category: '', photo: '', active: true },
    };
  },

  computed: {
    filtered() {
      if (!this.search) return this.tips;
      const q = this.search.toLowerCase();
      return this.tips.filter(t =>
        t.title.toLowerCase().includes(q) ||
        (t.body || '').toLowerCase().includes(q) ||
        (t.category || '').toLowerCase().includes(q)
      );
    },
  },

  methods: {
    openAdd() {
      this.editing = null;
      this.form = { title: '', body: '', category: '', photo: '', active: true };
      this.showDialog = true;
    },
    openEdit(tip) {
      this.editing = tip;
      this.form = { title: tip.title, body: tip.body, category: tip.category || '', photo: tip.photo || '', active: tip.active };
      this.showDialog = true;
    },
    closeDialog() { this.showDialog = false; },
    save() {
      if (this.editing) {
        this.$inertia.put(route('building-tips.update', this.editing.id), this.form, {
          onSuccess: () => this.closeDialog(),
        });
      } else {
        this.$inertia.post(route('building-tips.store'), this.form, {
          onSuccess: () => this.closeDialog(),
        });
      }
    },
    toggleActive(tip) {
      this.$inertia.put(route('building-tips.update', tip.id), { active: !tip.active });
    },
    confirmDelete(tip) { this.deleting = tip; this.showDelete = true; },
    doDelete() {
      this.$inertia.delete(route('building-tips.destroy', this.deleting.id), {
        onSuccess: () => { this.showDelete = false; this.deleting = null; },
      });
    },
  },
};
</script>
