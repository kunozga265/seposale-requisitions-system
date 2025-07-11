<template>


  <div v-if="accountGroup != null" class="page-section">
    <div class="page-section-header">
      <div class="page-section-title">
        [{{ accountGroup.data.code }}] {{ accountGroup.data.name }}
      </div>
    </div>
    <div class="page-section-content">
      <div class="card">
        <table class="w-full">
          <tr v-for="(account, index) in accountGroup.accounts" class="" v-if="account.total >= 0">

            <td class="ml-6 text-sm">
              {{ account.data.name }}
            </td>
            <td class="text-xs text-right">
              MK{{ numberWithCommas(account.total.toFixed(2)) }}
            </td>
          </tr>
          <tr v-if="accountGroup.accounts.length > 1">

            <td class="ml-6 text-sm text-right font-bold ">

            </td>
            <td class="py-1 text-sm text-right font-bold ">
              MK{{ numberWithCommas(accountGroup.total.toFixed(2)) }}
            </td>
          </tr>
        </table>

      </div>


    </div>
  </div>



</template>

<script>


export default {
  name: "StatementLine",
  props: ['accountGroup'],
  components: {

  },

  data() {
    return {
    }
  },

  computed: {
   

  },

  methods: {
    

  }
}
</script>

<style scoped></style>
