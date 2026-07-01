<template>
  <app-layout>
    <template #header>
      Reviews
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Reviews</span>
        </div>
      </li>
    </template>

    <template #actions>
      <primary-button @click.native="addDialog = true">
        Add Review
      </primary-button>
    </template>

    <!-- Add Review Dialog -->
    <dialog-modal :show="addDialog" @close="closeAddDialog">
      <template #title>Add Review</template>
      <template #content>
        <jet-validation-errors class="mb-4" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

          <div class="p-2 mb-2 md:col-span-2">
            <jet-label for="addVariant" value="Product Variant" />
            <select id="addVariant" v-model="addForm.product_variant_id"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
              <option value="">Select variant…</option>
              <option v-for="v in variants" :key="v.id" :value="v.id">{{ v.name }}</option>
            </select>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="addName" value="Reviewer Name" />
            <jet-input id="addName" type="text" class="block w-full" v-model="addForm.name"
              placeholder="e.g. John M." autocomplete="off" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="addLocation" value="Location (optional)" />
            <jet-input id="addLocation" type="text" class="block w-full" v-model="addForm.location"
              placeholder="e.g. Blantyre" autocomplete="off" />
          </div>

          <div class="p-2 mb-2">
            <jet-label for="addRating" value="Rating (1–5)" />
            <select id="addRating" v-model="addForm.rating"
              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
              <option value="">Select…</option>
              <option v-for="n in 5" :key="n" :value="n">{{ n }} {{ '★'.repeat(n) }}</option>
            </select>
          </div>

          <div class="p-2 mb-2">
            <jet-label for="addHelpful" value="Helpful Count" />
            <jet-input id="addHelpful" type="number" min="0" class="block w-full" v-model="addForm.helpful_count"
              autocomplete="off" />
          </div>

          <div class="p-2 mb-2 md:col-span-2">
            <jet-label for="addComment" value="Comment (optional)" />
            <textarea id="addComment" v-model="addForm.comment" rows="3"
              class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full text-sm p-2"
              placeholder="What did the customer say?"></textarea>
          </div>

          <div class="p-2 mb-2 flex gap-x-6">
            <label class="flex items-center gap-x-2 text-sm cursor-pointer">
              <input type="checkbox" v-model="addForm.verified" class="rounded border-gray-300 text-indigo-600" />
              Verified Purchase
            </label>
            <label class="flex items-center gap-x-2 text-sm cursor-pointer">
              <input type="checkbox" v-model="addForm.active" class="rounded border-gray-300 text-indigo-600" />
              Publish (Active)
            </label>
          </div>

        </div>
      </template>
      <template #footer>
        <secondary-button @click.native="closeAddDialog">Cancel</secondary-button>
        <primary-button class="ml-2" @click.native="saveAdd" :disabled="addForm.processing">
          Save Review
        </primary-button>
      </template>
    </dialog-modal>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All Reviews ({{ filteredReviews.length }})
            </div>
            <div class="flex gap-x-2 items-center flex-wrap">
              <select v-model="filterActive"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                <option value="">All</option>
                <option value="active">Active Only</option>
                <option value="inactive">Inactive Only</option>
              </select>
              <jet-input type="text" class="block" v-model="filterProduct"
                placeholder="Filter by product…" autocomplete="off" style="max-width:200px" />
            </div>
          </div>
          <div class="page-section-content">
            <div v-if="filteredReviews.length === 0" class="text-center text-gray-400 text-sm py-8">
              No reviews found
            </div>
            <div v-else class="card">
              <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-500 dark:text-gray-400">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                      <th class="p-2 heading-font">Product / Variant</th>
                      <th class="p-2 heading-font">Reviewer</th>
                      <th class="p-2 heading-font">Rating</th>
                      <th class="p-2 heading-font">Comment</th>
                      <th class="p-2 heading-font">Verified</th>
                      <th class="p-2 heading-font">Active</th>
                      <th class="p-2 heading-font">Date</th>
                      <th class="p-2 heading-font">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="r in filteredReviews" :key="r.id"
                      class="border-b hover:bg-gray-50 transition ease-in-out duration-200">
                      <td class="p-2">
                        <div class="font-medium text-gray-900 text-sm">{{ r.variant ? r.variant.name : '—' }}</div>
                        <div class="text-xs text-gray-400">{{ r.variant ? r.variant.product : '' }}</div>
                      </td>
                      <td class="p-2 text-sm">
                        <div>{{ r.name }}</div>
                        <div v-if="r.location" class="text-xs text-gray-400">{{ r.location }}</div>
                      </td>
                      <td class="p-2 text-sm">
                        <span class="text-amber-500">{{ '★'.repeat(r.rating) }}</span><span class="text-gray-300">{{ '★'.repeat(5 - r.rating) }}</span>
                      </td>
                      <td class="p-2 text-sm max-w-xs">
                        <div class="truncate max-w-xs" :title="r.comment">{{ r.comment || '—' }}</div>
                      </td>
                      <td class="p-2 text-center">
                        <span v-if="r.verified" class="text-xs px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Yes</span>
                        <span v-else class="text-xs text-gray-400">No</span>
                      </td>
                      <td class="p-2">
                        <button @click="toggleReviewActive(r)"
                          :class="r.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                          class="text-xs px-2 py-1 rounded-full font-medium">
                          {{ r.active ? 'Active' : 'Inactive' }}
                        </button>
                      </td>
                      <td class="p-2 text-xs text-gray-500">{{ formatDate(r.date) }}</td>
                      <td class="p-2">
                        <button @click="deleteReview(r)"
                          class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                          Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from "@/Jetstream/Button";
import SecondaryButton from "@/Jetstream/SecondaryButton";
import DialogModal from "@/Jetstream/DialogModal.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetValidationErrors from "@/Jetstream/ValidationErrors.vue";

export default {
  props: ['reviews', 'variants'],
  components: { AppLayout, PrimaryButton, SecondaryButton, DialogModal, JetInput, JetLabel, JetValidationErrors },
  data() {
    return {
      addDialog: false,
      filterActive: '',
      filterProduct: '',
      addForm: this.$inertia.form({
        product_variant_id: '',
        name: '',
        rating: '',
        comment: '',
        location: '',
        helpful_count: 0,
        verified: false,
        active: false,
      }),
    }
  },
  computed: {
    filteredReviews() {
      let list = this.reviews
      if (this.filterActive === 'active')   list = list.filter(r => r.active)
      if (this.filterActive === 'inactive') list = list.filter(r => !r.active)
      if (this.filterProduct.trim()) {
        const q = this.filterProduct.toLowerCase()
        list = list.filter(r =>
          (r.variant?.name || '').toLowerCase().includes(q) ||
          (r.variant?.product || '').toLowerCase().includes(q)
        )
      }
      return list
    },
  },
  methods: {
    formatDate(ts) {
      if (!ts) return '—'
      return new Date(ts * 1000).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })
    },
    closeAddDialog() {
      this.addDialog = false
      this.addForm.reset()
    },
    saveAdd() {
      this.addForm.post(this.route('reviews.store'), {
        onSuccess: () => this.closeAddDialog(),
      })
    },
    toggleReviewActive(r) {
      this.$inertia.put(this.route('reviews.update', { id: r.id }), { active: !r.active })
    },
    deleteReview(r) {
      if (!confirm('Delete review by "' + r.name + '"?')) return
      this.$inertia.delete(this.route('reviews.destroy', { id: r.id }))
    },
  }
}
</script>
