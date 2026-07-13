<template>
  <app-layout>
    <template #header>
      Rewards
    </template>

    <template #breadcrumbs>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Rewards</span>
        </div>
      </li>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 mb-4">
          <button @click="activeTab = 'withdrawals'"
            :class="activeTab === 'withdrawals'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium heading-font uppercase focus:outline-none">
            Withdrawal Requests
          </button>
          <button @click="activeTab = 'grant'"
            :class="activeTab === 'grant'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium heading-font uppercase focus:outline-none">
            Grant Reward
          </button>
          <button @click="activeTab = 'deduct'"
            :class="activeTab === 'deduct'
              ? 'border-b-2 border-indigo-600 text-indigo-600'
              : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 text-sm font-medium heading-font uppercase focus:outline-none">
            Deduct Reward
          </button>
          <inertia-link :href="route('rewards.product-rewards')"
            class="px-4 py-2 text-sm font-medium heading-font uppercase text-gray-500 hover:text-gray-700">
            Product Rewards
          </inertia-link>
        </div>

        <!-- Grant Reward Tab -->
        <div v-if="activeTab === 'grant'">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">Grant Reward to Client</div>
            </div>
            <div class="page-section-content">
              <div class="card p-4 max-w-lg">
                <p v-if="$page.props.flash && $page.props.flash.success"
                  class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
                  {{ $page.props.flash.success }}
                </p>
                <form @submit.prevent="submitGrant">
                  <div class="space-y-4">
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Client *</label>
                      <select v-model="grantForm.client_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled>Select client…</option>
                        <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Amount (MWK) *</label>
                      <input v-model.number="grantForm.amount" type="number" min="1" step="0.01" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g. 5000" />
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Debit wallet account *</label>
                      <select v-model="grantForm.wallet_account_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled>Select account…</option>
                        <option v-for="a in walletAccounts" :key="a.id" :value="a.id">
                          {{ a.name }} (MK {{ formatMoney(a.balance) }})
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Description</label>
                      <input v-model="grantForm.description" type="text"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g. Loyalty bonus — Q2 2026" />
                    </div>
                    <button type="submit" :disabled="grantSubmitting"
                      class="w-full bg-indigo-600 text-white text-sm font-semibold rounded-lg px-4 py-2 hover:bg-indigo-700 disabled:opacity-60">
                      {{ grantSubmitting ? 'Submitting…' : 'Grant Reward' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Deduct Reward Tab -->
        <div v-if="activeTab === 'deduct'">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">Deduct Reward from Client</div>
            </div>
            <div class="page-section-content">
              <div class="card p-4 max-w-lg">
                <p class="mb-4 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded p-3">
                  Use this to correct a reward that was mistakenly credited to the wrong client.
                  This removes the amount from their reward balance and reverses the accounting entries.
                </p>
                <p v-if="$page.props.flash && $page.props.flash.success"
                  class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded p-3">
                  {{ $page.props.flash.success }}
                </p>
                <form @submit.prevent="submitDeduct">
                  <div class="space-y-4">
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Client *</label>
                      <select v-model="deductForm.client_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled>Select client…</option>
                        <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Amount (MWK) *</label>
                      <input v-model.number="deductForm.amount" type="number" min="1" step="0.01" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g. 5000" />
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Credit wallet account *</label>
                      <select v-model="deductForm.wallet_account_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="" disabled>Select account…</option>
                        <option v-for="a in walletAccounts" :key="a.id" :value="a.id">
                          {{ a.name }} (MK {{ formatMoney(a.balance) }})
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-xs font-semibold text-gray-700 mb-1">Reason *</label>
                      <input v-model="deductForm.note" type="text" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="e.g. Reward credited to wrong client on Sale #1234 — correcting" />
                    </div>
                    <button type="submit" :disabled="deductSubmitting"
                      class="w-full bg-red-600 text-white text-sm font-semibold rounded-lg px-4 py-2 hover:bg-red-700 disabled:opacity-60">
                      {{ deductSubmitting ? 'Submitting…' : 'Deduct Reward' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Withdrawal Requests Tab -->
        <div v-if="activeTab === 'withdrawals'">
          <div class="page-section">
            <div class="page-section-header">
              <div class="page-section-title">
                Withdrawal Requests ({{ filteredRequests.length }})
              </div>
              <div class="flex gap-x-2 items-center">
                <select v-model="statusFilter"
                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2 dark:bg-gray-600 dark:border-gray-500 dark:text-white">
                  <option value="">All Statuses</option>
                  <option value="pending">Pending</option>
                  <option value="approved">Approved</option>
                  <option value="paid">Paid</option>
                  <option value="rejected">Rejected</option>
                </select>
              </div>
            </div>
            <div class="page-section-content">
              <div v-if="filteredRequests.length === 0" class="text-center text-gray-400 text-sm py-8">
                No withdrawal requests found
              </div>
              <div v-else class="card">
                <div class="overflow-x-auto">
                  <table class="w-full text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                      <tr>
                        <th class="p-2 heading-font">Client</th>
                        <th class="p-2 heading-font">Amount (MWK)</th>
                        <th class="p-2 heading-font">Status</th>
                        <th class="p-2 heading-font">Payout Method</th>
                        <th class="p-2 heading-font">Notes</th>
                        <th class="p-2 heading-font">Requested</th>
                        <th class="p-2 heading-font">Approved By</th>
                        <th class="p-2 heading-font">Paid By</th>
                        <th class="p-2 heading-font">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="req in filteredRequests" :key="req.id"
                        class="border-b hover:bg-gray-50 transition ease-in-out duration-200">
                        <td class="p-2 font-medium text-gray-900">{{ req.client.name }}</td>
                        <td class="p-2">{{ formatMoney(req.amount) }}</td>
                        <td class="p-2">
                          <span :class="statusClass(req.status)"
                            class="px-2 py-0.5 text-xs rounded-full font-medium">
                            {{ req.status }}
                          </span>
                        </td>
                        <td class="p-2 text-sm">
                          <span v-if="req.paymentMethod" class="font-medium text-gray-900">{{ req.paymentMethod }}</span>
                          <span v-else class="text-gray-400">—</span>
                          <div v-if="req.payoutDetails && Object.keys(req.payoutDetails).length"
                            class="mt-1 space-y-0.5">
                            <div v-for="(val, key) in req.payoutDetails" :key="key"
                              class="text-xs text-gray-500">
                              <span class="capitalize">{{ key.replace(/_/g, ' ') }}:</span>
                              <span class="font-medium text-gray-700"> {{ val }}</span>
                            </div>
                          </div>
                        </td>
                        <td class="p-2 text-sm text-gray-600 max-w-xs truncate">{{ req.notes || '—' }}</td>
                        <td class="p-2 text-sm">{{ formatDate(req.createdAt) }}</td>
                        <td class="p-2 text-sm">{{ req.approvedBy || '—' }}<br>
                          <span v-if="req.approvedAt" class="text-xs text-gray-400">{{ formatDate(req.approvedAt) }}</span>
                        </td>
                        <td class="p-2 text-sm">{{ req.paidBy || '—' }}<br>
                          <span v-if="req.paidAt" class="text-xs text-gray-400">{{ formatDate(req.paidAt) }}</span>
                        </td>
                        <td class="p-2">
                          <div class="flex gap-x-2 flex-wrap">
                            <button v-if="req.status === 'pending'"
                              @click="approveRequest(req.id)"
                              class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                              Approve
                            </button>
                            <button v-if="req.status === 'pending'"
                              @click="rejectRequest(req.id)"
                              class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                              Reject
                            </button>
                            <template v-if="req.status === 'approved'">
                              <div v-if="payingId === req.id" class="flex flex-col gap-1 min-w-[160px]">
                                <select v-model="payWalletAccountId"
                                  class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-green-500 focus:border-green-500">
                                  <option value="" disabled>Select wallet…</option>
                                  <option v-for="a in walletAccounts" :key="a.id" :value="a.id">
                                    {{ a.name }} (MK {{ formatMoney(a.balance) }})
                                  </option>
                                </select>
                                <div class="flex gap-1">
                                  <button @click="confirmPay(req.id)"
                                    :disabled="!payWalletAccountId"
                                    class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50">
                                    Confirm
                                  </button>
                                  <button @click="payingId = null; payWalletAccountId = ''"
                                    class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                    Cancel
                                  </button>
                                </div>
                              </div>
                              <button v-else
                                @click="payingId = req.id; payWalletAccountId = ''"
                                class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                Mark Paid
                              </button>
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
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from "@/Jetstream/Button";
import SecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  props: ['withdrawalRequests', 'clients', 'walletAccounts'],
  components: { AppLayout, PrimaryButton, SecondaryButton },
  data() {
    return {
      activeTab: 'withdrawals',
      statusFilter: '',
      payingId: null,
      payWalletAccountId: '',
      grantForm: {
        client_id:         '',
        amount:            '',
        wallet_account_id: '',
        description:       '',
      },
      grantSubmitting: false,
      deductForm: {
        client_id:         '',
        amount:            '',
        wallet_account_id: '',
        note:              '',
      },
      deductSubmitting: false,
    }
  },
  computed: {
    filteredRequests() {
      if (!this.statusFilter) return this.withdrawalRequests
      return this.withdrawalRequests.filter(r => r.status === this.statusFilter)
    },
  },
  methods: {
    statusClass(status) {
      return {
        'pending':  'bg-amber-100 text-amber-800',
        'approved': 'bg-blue-100 text-blue-800',
        'paid':     'bg-green-100 text-green-800',
        'rejected': 'bg-red-100 text-red-800',
      }[status] || 'bg-gray-100 text-gray-800'
    },
    formatMoney(amount) {
      return new Intl.NumberFormat('en-MW', { minimumFractionDigits: 2 }).format(amount)
    },
    formatDate(dt) {
      if (!dt) return '—'
      return new Date(dt).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })
    },
    submitGrant() {
      if (this.grantSubmitting) return
      this.grantSubmitting = true
      this.$inertia.post(this.route('rewards.grant'), this.grantForm, {
        preserveScroll: true,
        onSuccess: () => {
          this.grantForm = { client_id: '', amount: '', wallet_account_id: '', description: '' }
        },
        onFinish: () => { this.grantSubmitting = false },
      })
    },
    submitDeduct() {
      if (this.deductSubmitting) return
      this.deductSubmitting = true
      this.$inertia.post(this.route('rewards.deduct'), this.deductForm, {
        preserveScroll: true,
        onSuccess: () => {
          this.deductForm = { client_id: '', amount: '', wallet_account_id: '', note: '' }
        },
        onFinish: () => { this.deductSubmitting = false },
      })
    },
    approveRequest(id) {
      if (!confirm('Approve this withdrawal request?')) return
      this.$inertia.post(this.route('rewards.withdrawal.approve', { id }))
    },
    rejectRequest(id) {
      if (!confirm('Reject this withdrawal request?')) return
      this.$inertia.post(this.route('rewards.withdrawal.reject', { id }))
    },
    confirmPay(id) {
      if (!this.payWalletAccountId) return
      this.$inertia.post(this.route('rewards.withdrawal.pay', { id }), {
        wallet_account_id: this.payWalletAccountId,
      }, {
        preserveScroll: true,
        onSuccess: () => { this.payingId = null; this.payWalletAccountId = '' },
      })
    },
  }
}
</script>
