<template>
  <app-layout>
    <template #header>
      Policies
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
          <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Policies</span>
        </div>
      </li>
    </template>

    <template #actions>
      <inertia-link :href="route('policies.create')">
        <primary-button>
          New Policy
        </primary-button>
      </inertia-link>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

        <div class="page-section">
          <div class="page-section-header">
            <div class="page-section-title">
              All
            </div>
          </div>
          <div class="page-section-content">

            <div v-if="policies.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
              No Policies Found
            </div>
            <div v-else>
              <div class="card">
                <div class="p-2 mb-2 relative ">

                  <table class="w-full  text-left text-gray-500 dark:text-gray-400">
                    <thead class="mb-8 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">

                      <tr>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Title</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-left">Slug</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-center">Active</th>
                        <th scope="col" class="p-2 pb-0 heading-font text-right">Actions</th>
                      </tr>

                    </thead>
                    <tbody class="pt-8">

                      <tr class="border-b hover:bg-gray-100 transition ease-in-out duration-200"
                        v-for="(policy, index) in policies" :key="index">
                        <td class="p-2 text-left ">{{ policy.title }}</td>
                        <td class="p-2 text-left text-gray-400">{{ policy.slug }}</td>
                        <td class="p-2 text-center ">
                          <span :class="policy.active ? 'text-green-600' : 'text-gray-400'">
                            {{ policy.active ? 'Yes' : 'No' }}
                          </span>
                        </td>
                        <td class="p-2 text-right ">
                          <inertia-link :href="route('policies.edit', { id: policy.id })"
                            class="text-blue-600 hover:text-blue-800 text-sm mr-3">
                            Edit
                          </inertia-link>
                          <button type="button" class="text-red-600 hover:text-red-800 text-sm"
                            @click="destroy(policy.id)">
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
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import PrimaryButton from "@/Jetstream/Button";

export default {
  props: [
    'policies',
  ],
  components: {
    AppLayout,
    PrimaryButton
  },
  methods: {
    destroy(id) {
      if (!confirm('Delete this policy? This cannot be undone.')) return

      this.$inertia.delete(this.route('policies.destroy', { id }))
    },
  }
}
</script>
