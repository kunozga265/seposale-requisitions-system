<template>

  <span class="font-bold" :class="{ 'text-red-500': value < 0, 'text-green-500': value > 0, }">
    {{ value > 0 ? '+' : '' }}{{ value < 0 ? '-' : '' }}{{ numberWithCommas(Math.abs(value).toFixed(2)) }} </span>

</template>

<script>


export default {
  name: "Profit",
  props: ['value'],
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
