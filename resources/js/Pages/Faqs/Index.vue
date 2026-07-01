<template>
  <app-layout>
    <template #header>
      FAQs
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">FAQs</span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">

        <!-- Action bar -->
        <div class="flex items-center justify-between mb-4">
          <input v-model="search" type="text" placeholder="Search FAQs..."
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
          <jet-button @click="openAdd">Add FAQ</jet-button>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Question</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Category</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Order</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-600">Active</th>
                <th class="px-4 py-3"></th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <tr v-for="faq in filtered" :key="faq.id" class="hover:bg-gray-50">
                <td class="px-4 py-3 max-w-xs">
                  <p class="font-medium truncate">{{ faq.question }}</p>
                  <p class="text-gray-400 text-xs truncate">{{ faq.answer.substring(0, 80) }}…</p>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ faq.category || '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ faq.sort_order }}</td>
                <td class="px-4 py-3">
                  <button @click="toggleActive(faq)"
                    :class="faq.active ? 'bg-green-500' : 'bg-gray-300'"
                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none">
                    <span :class="faq.active ? 'translate-x-6' : 'translate-x-1'"
                      class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                  </button>
                </td>
                <td class="px-4 py-3 flex gap-2 justify-end">
                  <button @click="openEdit(faq)" class="text-indigo-600 hover:underline text-xs">Edit</button>
                  <button @click="confirmDelete(faq)" class="text-red-500 hover:underline text-xs">Delete</button>
                </td>
              </tr>
              <tr v-if="filtered.length === 0">
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">No FAQs found.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Add/Edit Dialog -->
        <dialog-modal :show="showDialog" @close="closeDialog">
          <template #title>{{ editing ? 'Edit FAQ' : 'Add FAQ' }}</template>
          <template #content>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                <input v-model="form.question" type="text"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Answer</label>
                <textarea v-model="form.answer" rows="4"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"></textarea>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                  <input v-model="form.category" type="text"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                  <input v-model.number="form.sort_order" type="number" min="0"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>
              </div>
              <div class="flex items-center gap-2">
                <input v-model="form.active" type="checkbox" id="faq-active" class="rounded" />
                <label for="faq-active" class="text-sm text-gray-700">Active</label>
              </div>
            </div>
          </template>
          <template #footer>
            <jet-secondary-button @click="closeDialog">Cancel</jet-secondary-button>
            <jet-button class="ml-2" @click="save" :disabled="!form.question || !form.answer">
              {{ editing ? 'Update' : 'Add' }}
            </jet-button>
          </template>
        </dialog-modal>

        <!-- Delete confirm -->
        <confirmation-modal :show="showDelete" @close="showDelete = false">
          <template #title>Delete FAQ</template>
          <template #content>Are you sure you want to delete this FAQ?</template>
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
// import { useForm } from '@inertiajs/inertia-vue3';

export default {
  components: { AppLayout, JetButton, JetSecondaryButton, JetDangerButton, DialogModal, ConfirmationModal },

  props: {
    faqs: { type: Array, default: () => [] },
  },

  data() {
    return {
      search: '',
      showDialog: false,
      showDelete: false,
      editing: null,
      deleting: null,
      form: { question: '', answer: '', category: '', sort_order: 0, active: true },
    };
  },

  computed: {
    filtered() {
      if (!this.search) return this.faqs;
      const q = this.search.toLowerCase();
      return this.faqs.filter(f =>
        f.question.toLowerCase().includes(q) ||
        (f.answer || '').toLowerCase().includes(q) ||
        (f.category || '').toLowerCase().includes(q)
      );
    },
  },

  methods: {
    openAdd() {
      this.editing = null;
      this.form = { question: '', answer: '', category: '', sort_order: 0, active: true };
      this.showDialog = true;
    },
    openEdit(faq) {
      this.editing = faq;
      this.form = { question: faq.question, answer: faq.answer, category: faq.category || '', sort_order: faq.sort_order, active: faq.active };
      this.showDialog = true;
    },
    closeDialog() {
      this.showDialog = false;
    },
    save() {
      if (this.editing) {
        this.$inertia.put(route('faqs.update', this.editing.id), this.form, {
          onSuccess: () => this.closeDialog(),
        });
      } else {
        this.$inertia.post(route('faqs.store'), this.form, {
          onSuccess: () => this.closeDialog(),
        });
      }
    },
    toggleActive(faq) {
      this.$inertia.put(route('faqs.update', faq.id), { active: !faq.active });
    },
    confirmDelete(faq) {
      this.deleting = faq;
      this.showDelete = true;
    },
    doDelete() {
      this.$inertia.delete(route('faqs.destroy', this.deleting.id), {
        onSuccess: () => { this.showDelete = false; this.deleting = null; },
      });
    },
  },
};
</script>
