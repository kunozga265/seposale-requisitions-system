<template>
  <div>
    <jet-banner/>

    <div class="min-h-screen bg-gray-100">

      <nav class="bg-gray system-nav">
        <!-- Aside -->
        <aside :class="{'extend': open}" class="bg-gray-50 rounded dark:bg-gray-800 sidebar" aria-label="Sidebar">
          <div class="overflow-y-auto ">
            <div class="header">
              <div class="p-2 flex justify-end navigation-buttons">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                  <svg class="h-6 w-6" stroke="white" fill="none" viewBox="0 0 24 24">
                    <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
              <div class="flex justify-center">
                <div class=" h-24 w-24 flex justify-center items-center rounded-full bg-blue-700 cursor">
                  <i style="font-size: 30px;" class="mdi mdi-account-circle text-white"></i>
                </div>
                <!-- <div class="logo-variant"> -->
                <!-- <img :src="fileUrl('images/logo-variant.jpg')" alt="Geoserve Engineering Group Variant Logo"> -->
                <!-- </div> -->
              </div>
              <div class="text-center pt-2 py-4" v-if="$page.props.auth">
                <div class="heading-font text-white">{{ $page.props.auth.data.firstName }}
                  {{ $page.props.auth.data.middleName }} {{ $page.props.auth.data.lastName }}
                </div>
                <div class="heading-font text-white text-sm">{{ $page.props.auth.data.position.title }}</div>
              </div>
            </div>

            <ul class="px-10 p-6">
              <li>
                <a :href="route('dashboard')"
                   class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 ">
                  <i class="text-lg mdi mdi-view-dashboard"></i>
                  <span class="ml-3">Dashboard</span>
                </a>
              </li>

                <li v-if="checkRole($page.props.auth.data,'accountant') || checkRole($page.props.auth.data,'management')">
                    <div @click="accounts = !accounts"
                         class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <div>
                            <i class="text-lg mdi mdi-bank"></i>
                            <span class="ml-3">Accounts</span>
                        </div>
                        <div>
                            <i class="text-lg mdi" :class="{'mdi-menu-down':!accounts, 'mdi-menu-up': accounts}"></i>
                        </div>
                    </div>
                    <ul v-show="accounts">
                        <li>
                            <a :href="route('accounts.index')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Bank Accounts
                            </a>
                        </li>
                        <li >
                            <a :href="route('finance')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Pending Requisitions
                            </a>
                        </li>
                        <li>
                            <a :href="route('payables.index')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Payables
                            </a>
                        </li>
                        <li>
                            <a :href="route('expenses.index')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Expenses
                            </a>
                        </li>

                    </ul>
                </li>

              <li>
                <div @click="requests = !requests"
                     class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer ">
                  <div>
                    <i class="text-lg mdi mdi-book-alphabet"></i>
                    <span class="ml-3">Requisitions</span>
                  </div>
                  <div>
                    <i class="text-lg mdi" :class="{'mdi-menu-down':!requests, 'mdi-menu-up': requests}"></i>
                  </div>
                </div>
                <ul v-show="requests">
                  <li>
                    <a :href="route('index')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      All
                    </a>
                  </li>
                  <li>
                    <a :href="route('approved')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      Approved
                    </a>
                  </li>

                </ul>
              </li>
              <!--                            <li>-->
              <!--                                <a :href="route('index')" class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 ">-->
              <!--                                    <i class="text-lg mdi mdi-book-alphabet"></i>-->
              <!--                                    <span class="ml-3">All Requests</span>-->
              <!--                                </a>-->
              <!--                            </li>-->
              <!--<li>
                  <a href="#" class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      <i class="text-lg mdi mdi-book-clock"></i>
                      <span class="ml-3">Pending</span>
                  </a>
              </li>-->
              <!--                            <li>-->
              <!--                                <a :href="route('approved')"  class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">-->
              <!--                                    <i class="text-lg mdi mdi-book-check"></i>-->
              <!--                                    <span class="ml-3">Approved Requests</span>-->
              <!--                                </a>-->
              <!--                            </li>-->



              <li>
                <div @click="sales = !sales"
                     class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                  <div>
                    <i class="text-lg mdi mdi-finance"></i>
                    <span class="ml-3">Sales</span>
                  </div>
                  <div>
                    <i class="text-lg mdi" :class="{'mdi-menu-down':!sales, 'mdi-menu-up': sales}"></i>
                  </div>
                </div>
                <ul v-show="sales">
                  <li>
                    <a :href="route('sales.index',{section:'tabular'})"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      All
                    </a>
                  </li>
                  <li>
                    <a :href="route('receipts.index')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      Receipts
                    </a>
                  </li>
                  <li>
                    <a :href="route('invoices.index')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      Invoices
                    </a>
                  </li>
                  <li>
                    <a :href="route('quotations.index')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      Quotations
                    </a>
                  </li>
                  <li>
                    <a :href="route('products.index')"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                      Products
                    </a>
                  </li>


                </ul>
              </li>

                <li>
                    <a :href="route('clients.index')"
                       class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 ">
                        <i class="text-lg mdi mdi-account-group"></i>
                        <span class="ml-3">Clients</span>
                    </a>
                </li>

              <li>
                <div @click="sites = !sites"
                     class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                  <div>
                    <i class="text-lg mdi mdi-shopping"></i>
                    <span class="ml-3">One Stop Shops</span>
                  </div>
                  <div>
                    <i class="text-lg mdi" :class="{'mdi-menu-down':!sites, 'mdi-menu-up': sites}"></i>
                  </div>
                </div>
                <ul v-show="sites">
                  <li v-for="(site, index) in $page.props.sites" :key="index">
                    <a :href="route('sites.overview',{code:site.code})"
                       class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                        {{ site.name }}
                    </a>
                  </li>

                </ul>
              </li>



                <li>
                    <div @click="operations = !operations"
                         class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                        <div>
                            <i class="text-lg mdi mdi-dump-truck"></i>
                            <span class="ml-3">Operations</span>
                        </div>
                        <div>
                            <i class="text-lg mdi" :class="{'mdi-menu-down':!operations, 'mdi-menu-up': operations}"></i>
                        </div>
                    </div>
                    <ul v-show="operations">
                        <li>
                            <a :href="route('deliveries.index')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Deliveries
                            </a>
                        </li>
                        <li>
                            <a :href="route('transporters.index')"
                               class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                                Transporters
                            </a>
                        </li>
                    </ul>
                </li>

<!--               <li>-->
<!--                   <div @click="operations = !operations"-->
<!--                        class="mb-2 flex items-center justify-between p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 ">-->
<!--                       <div>-->
<!--                           <i class="text-lg mdi mdi-dump-truck"></i>-->
<!--                           <span class="ml-3">Operations</span>-->
<!--                       </div>-->
<!--                       <div>-->
<!--                           <i class="text-lg mdi" :class="{'mdi-menu-down':!operations, 'mdi-menu-up': operations}"></i>-->
<!--                       </div>-->
<!--                   </div>-->
<!--                   <ul v-show="operations">-->
<!--                       <li>-->
<!--                           <a :href="route('deliveries.index',{filter:'processing'})"-->
<!--                              class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">-->
<!--                               Processing-->
<!--                           </a>-->
<!--                       </li>-->

<!--                       <li>-->
<!--                           <a :href="route('deliveries.index',{filter:'completed'})"-->
<!--                              class="block w-full ml-6 p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">-->
<!--                               Completed-->
<!--                           </a>-->
<!--                       </li>-->


<!--                   </ul>-->
<!--               </li>-->



             <li v-if="checkRole($page.props.auth.data,'administrator') || checkRole($page.props.auth.data,'management')">
               <a :href="route('users')"
                  class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                 <i class="text-lg mdi mdi-account-supervisor-circle"></i>
                 <span class="ml-3">Users</span>
               </a>
             </li>
             <!-- <li>
                 <a :href="route('projects')" class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                     <i class="text-lg mdi mdi-home-group"></i>
                     <span class="ml-3">Projects</span>
                 </a>
             </li>
             <li>
                 <a :href="route('vehicles')" class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                     <i class="text-lg mdi mdi-car-multiple"></i>
                     <span class="ml-3">Vehicles</span>
                 </a>
             </li> -->
              <li v-if="checkRole($page.props.auth.data,'accountant') || checkRole($page.props.auth.data,'administrator') || checkRole($page.props.auth.data,'management')">
                <a :href="route('reports')"
                   class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                  <i class="text-lg mdi mdi-chart-timeline"></i>
                  <span class="ml-3">Reports</span>
                </a>
              </li>
              <li>
                <a :href="route('profile.show')"
                   class="mb-2 flex items-center p-2 text-sm font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                  <i class="text-lg mdi mdi-account"></i>
                  <span class="ml-3">Profile</span>
                </a>
              </li>

            </ul>
          </div>
        </aside>

        <!-- Primary Navigation Menu -->
        <div class="mx-auto  shadow navbar">
          <div class="flex justify-between h-16">
            <div class="flex">
              <div class="ml-3 flex items-center">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 focus:outline-none focus:text-gray-500 transition duration-150 ease-in-out">
                  <span class="material-icons">short_text</span>
                </button>
              </div>
              <!-- Logo -->
              <div class="flex-shrink-0 flex items-center">
                <inertia-link :href="route('dashboard')">
                  <jet-application-mark class="block h-9 w-auto"/>
                </inertia-link>
              </div>
            </div>

            <div class="flex items-center">

              <inertia-link :href="route('notifications')">
                <div class="ml-3 relative hover:cursor-pointer cursor">
                  <div v-show="$page.props.notificationsCount > 0" style="font-size:10px"
                       class="absolute right-0 h-4 w-4 rounded-full text-white font-bold grid justify-items-center text-xs bg-red-500 flex items-center ">
                    {{ $page.props.notificationsCount }}
                  </div>
                  <div class="h-9 w-9 rounded-full  grid justify-items-center flex items-center hover:cursor-pointer">
                    <img style="height:28px" :src="fileUrl('images/notifications.svg')" alt="Notifications Icon">
                  </div>
                </div>
              </inertia-link>

              <!-- Settings Dropdown -->
              <div class="mx-3">
                <jet-dropdown align="right" width="48">
                  <template #trigger>
                    <div class=" h-10 w-10 flex justify-center items-center rounded-full bg-blue-700 cursor">
                      <i class="mdi mdi-account-circle text-white"></i>
                    </div>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Manage Account
                    </div>

                    <jet-dropdown-link :href="route('profile.show')">
                      Profile
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <div @click="logout"
                         class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out hover:cursor-pointer cursor">
                      Logout
                    </div>
                  </template>
                </jet-dropdown>
              </div>
            </div>
          </div>
        </div>
      </nav>

      <div class="system-content">
        <!-- Page Heading -->
        <header class="" v-if="$slots.header">
          <div class="max-w-7xl mx-auto p-12 py-6 px-4 sm:px-6 lg:px-8">
            <div>
              <h2 class="text-center md:text-left font-semibold text-gray-800 leading-tight heading-font">
                <slot name="header"></slot>
              </h2>
              <nav class="flex justify-center md:justify-start" aria-label="Breadcrumb">
                <ol class="inline-flex items-center">
                  <li class="inline-flex items-center">
                    <a :href="route('dashboard')"
                       class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                      Home
                    </a>
                  </li>
                  <slot name="breadcrumbs"></slot>
                </ol>
              </nav>

            </div>
            <div class="text-center" v-if="$slots.actions">
              <slot name="actions"></slot>
            </div>
          </div>
        </header>

        <!-- Page Content -->
        <main>
          <slot></slot>
        </main>
      </div>


      <!-- Modal Portal -->
      <portal-target name="modal" multiple>
      </portal-target>
    </div>

    <toast/>

  </div>
</template>

<script>
import JetApplicationMark from '@/Jetstream/ApplicationMark'
import JetBanner from '@/Jetstream/Banner'
import JetDropdown from '@/Jetstream/Dropdown'
import JetDropdownLink from '@/Jetstream/DropdownLink'
import JetNavLink from '@/Jetstream/NavLink'
import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
import Toast from "@/Components/Toast";

export default {
  components: {
    JetApplicationMark,
    JetBanner,
    JetDropdown,
    JetDropdownLink,
    JetNavLink,
    JetResponsiveNavLink,
    Toast
  },

  data() {
    return {
      showingNavigationDropdown: false,
      open: false,
      requests: false,
      accounts: false,
      sales: false,
      sites: false,
      operations: false,
    }
  },

  methods: {
    logout() {
      this.$inertia.post(route('logout'));
    },
  }
}
</script>
