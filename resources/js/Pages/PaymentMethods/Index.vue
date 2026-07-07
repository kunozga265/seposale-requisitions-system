<template>
  <app-layout>
    <template #header>
      Payment Methods
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
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">
            Payment Methods
          </span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-5xl mx-auto px-2 sm:px-6 lg:px-8">

        <p v-if="$page.props.flash && $page.props.flash.success"
          class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
          {{ $page.props.flash.success }}
        </p>

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">Withdrawal Settings</div>
            <p class="text-xs text-gray-500 mt-1">
              Toggle which payment methods clients can use when requesting a reward withdrawal, and define the
              fields that need to be filled in for each method.
            </p>
          </div>

          <div class="page-section-content">
            <div class="space-y-4">
              <div v-for="method in localMethods" :key="method.id"
                class="card p-4 border rounded-lg"
                :class="method.for_withdrawal ? 'border-indigo-200 bg-indigo-50' : 'border-gray-200'">

                <!-- Header row -->
                <div class="flex items-center justify-between gap-4">
                  <div class="flex items-center gap-3">
                    <img v-if="method.photo" :src="method.photo" :alt="method.name" class="h-8 w-auto object-contain" />
                    <span class="font-semibold text-gray-800">{{ method.name }}</span>
                  </div>

                  <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">Allow for withdrawal</span>
                    <div class="relative">
                      <input type="checkbox" v-model="method.for_withdrawal" class="sr-only"
                        @change="saveMethod(method)" />
                      <div class="w-10 h-6 rounded-full transition-colors"
                        :class="method.for_withdrawal ? 'bg-indigo-600' : 'bg-gray-300'"></div>
                      <div class="absolute top-1 left-1 w-4 h-4 rounded-full bg-white shadow transition-transform"
                        :class="method.for_withdrawal ? 'translate-x-4' : 'translate-x-0'"></div>
                    </div>
                  </label>
                </div>

                <!-- Field builder (shown when withdrawal is enabled) -->
                <div v-if="method.for_withdrawal" class="mt-4 border-t border-indigo-200 pt-4">
                  <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-medium text-gray-700">Required Fields from Client</p>
                    <button type="button" @click="addField(method)"
                      class="text-xs px-2 py-1 bg-indigo-100 text-indigo-700 rounded hover:bg-indigo-200 font-medium">
                      + Add Field
                    </button>
                  </div>

                  <div v-if="!method.withdrawal_fields || method.withdrawal_fields.length === 0"
                    class="text-xs text-gray-400 italic">
                    No fields defined. Add at least one field (e.g. Phone Number for mobile money methods).
                  </div>

                  <div v-else class="space-y-2">
                    <div v-for="(field, idx) in method.withdrawal_fields" :key="idx"
                      class="flex items-center gap-2 flex-wrap">
                      <input v-model="field.key" placeholder="key (e.g. phone)" maxlength="50"
                        class="w-28 border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500" />
                      <input v-model="field.label" placeholder="Label (e.g. Phone Number)" maxlength="100"
                        class="flex-1 min-w-24 border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500" />
                      <select v-model="field.type"
                        class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="text">Text</option>
                        <option value="tel">Tel</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                      </select>
                      <label class="flex items-center gap-1 text-xs text-gray-600">
                        <input type="checkbox" v-model="field.required" class="rounded border-gray-300" />
                        Required
                      </label>
                      <button type="button" @click="removeField(method, idx)"
                        class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                        Remove
                      </button>
                    </div>
                  </div>

                  <button type="button" @click="saveMethod(method)"
                    class="mt-3 text-xs px-3 py-1.5 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
                    Save Fields
                  </button>
                </div>
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

export default {
  components: { AppLayout },
  props: ['paymentMethods'],
  data() {
    return {
      localMethods: this.paymentMethods.map(m => ({
        ...m,
        withdrawal_fields: m.withdrawal_fields ? [...m.withdrawal_fields.map(f => ({ ...f }))] : [],
      })),
    }
  },
  methods: {
    addField(method) {
      if (!method.withdrawal_fields) method.withdrawal_fields = []
      method.withdrawal_fields.push({ key: '', label: '', type: 'text', required: true })
    },
    removeField(method, idx) {
      method.withdrawal_fields.splice(idx, 1)
      this.saveMethod(method)
    },
    saveMethod(method) {
      this.$inertia.put(
        this.route('payment-methods.update', { id: method.id }),
        {
          for_withdrawal: method.for_withdrawal,
          withdrawal_fields: method.for_withdrawal && method.withdrawal_fields?.length
            ? method.withdrawal_fields
            : null,
        },
        { preserveScroll: true }
      )
    },
  },
}
</script>
