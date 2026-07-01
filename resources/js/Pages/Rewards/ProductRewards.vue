<template>
  <app-layout>
    <template #header>
      Product Rewards
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            <inertia-link :href="route('rewards.index')" class="hover:underline">Rewards</inertia-link>
            &rsaquo; Product Rewards
          </span>
        </div>
      </li>
    </template>

    <template #actions>
      <primary-button @click.native="addDialog = true">
        Add Reward
      </primary-button>
    </template>

    <!-- Add Reward Dialog -->
    <dialog-modal :show="addDialog" @close="addDialog = false">
      <template #title>Add Product Reward</template>
      <template #content>
        <jet-validation-errors class="mb-4" />
        <div class="p-2 mb-2">
          <jet-label for="addVariant" value="Product Variant" />
          <select id="addVariant" v-model="addForm.product_variant_id"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
            <option value="">Select variant…</option>
            <option v-for="v in variantsWithoutReward" :key="v.id" :value="v.id">
              {{ v.product ? v.product + ' — ' : '' }}{{ v.name }}
            </option>
          </select>
        </div>
        <div class="p-2 mb-2">
          <jet-label for="addAmount" value="Reward Amount (MWK per unit)" />
          <jet-input id="addAmount" type="number" step="0.01" min="0.01" class="block w-full"
            v-model="addForm.reward_amount" placeholder="e.g. 10000" autocomplete="off" />
        </div>
      </template>
      <template #footer>
        <secondary-button @click.native="addDialog = false">Cancel</secondary-button>
        <primary-button class="ml-2" @click.native="saveAdd" :disabled="addForm.processing">
          Save
        </primary-button>
      </template>
    </dialog-modal>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              Variants with Rewards ({{ variantsWithReward.length }})
            </div>
          </div>
          <div class="page-section-content">
            <div v-if="variantsWithReward.length === 0" class="text-center text-gray-400 text-sm py-8">
              No product rewards configured yet
            </div>
            <div v-else class="card">
              <div class="overflow-x-auto">
                <table class="w-full text-left text-gray-500 dark:text-gray-400">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                      <th class="p-2 heading-font">Product</th>
                      <th class="p-2 heading-font">Variant</th>
                      <th class="p-2 heading-font">Reward (MWK/unit)</th>
                      <th class="p-2 heading-font">Active</th>
                      <th class="p-2 heading-font">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="v in variantsWithReward" :key="v.id"
                      class="border-b hover:bg-gray-50 transition ease-in-out duration-200">
                      <td class="p-2 text-sm text-gray-600">{{ v.product || '—' }}</td>
                      <td class="p-2 font-medium text-gray-900">{{ v.name }}</td>
                      <td class="p-2">
                        <input v-if="editingId === v.rewardId" type="number" step="0.01" min="0.01"
                          v-model="editAmount"
                          class="border border-gray-300 rounded p-1 text-sm w-32 focus:ring-blue-500 focus:border-blue-500" />
                        <span v-else>{{ formatMoney(v.rewardAmount) }}</span>
                      </td>
                      <td class="p-2">
                        <button @click="toggleActive(v)"
                          :class="v.active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'"
                          class="text-xs px-2 py-1 rounded-full font-medium">
                          {{ v.active ? 'Active' : 'Inactive' }}
                        </button>
                      </td>
                      <td class="p-2">
                        <div class="flex gap-x-2">
                          <template v-if="editingId === v.rewardId">
                            <button @click="saveEdit(v)" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">Save</button>
                            <button @click="cancelEdit" class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200">Cancel</button>
                          </template>
                          <template v-else>
                            <button @click="startEdit(v)" class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200">Edit</button>
                            <button @click="deleteReward(v)" class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">Remove</button>
                          </template>
                        </div>
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
  props: ['variants'],
  components: { AppLayout, PrimaryButton, SecondaryButton, DialogModal, JetInput, JetLabel, JetValidationErrors },
  data() {
    return {
      addDialog: false,
      addForm: this.$inertia.form({ product_variant_id: '', reward_amount: '' }),
      editingId: null,
      editAmount: '',
    }
  },
  computed: {
    variantsWithReward() {
      return this.variants.filter(v => v.hasReward)
    },
    variantsWithoutReward() {
      return this.variants.filter(v => !v.hasReward)
    },
  },
  methods: {
    formatMoney(amount) {
      return new Intl.NumberFormat('en-MW', { minimumFractionDigits: 2 }).format(amount)
    },
    saveAdd() {
      this.addForm.post(this.route('rewards.product-rewards.store'), {
        onSuccess: () => {
          this.addDialog = false
          this.addForm.reset()
        },
      })
    },
    startEdit(v) {
      this.editingId = v.rewardId
      this.editAmount = v.rewardAmount
    },
    cancelEdit() {
      this.editingId = null
      this.editAmount = ''
    },
    saveEdit(v) {
      this.$inertia.put(this.route('rewards.product-rewards.update', { id: v.rewardId }), {
        reward_amount: this.editAmount,
      }, {
        onSuccess: () => this.cancelEdit(),
      })
    },
    toggleActive(v) {
      this.$inertia.put(this.route('rewards.product-rewards.update', { id: v.rewardId }), {
        active: !v.active,
      })
    },
    deleteReward(v) {
      if (!confirm('Remove reward for ' + v.name + '?')) return
      this.$inertia.delete(this.route('rewards.product-rewards.destroy', { id: v.rewardId }))
    },
  }
}
</script>
