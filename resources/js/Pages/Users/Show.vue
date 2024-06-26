<template>
    <app-layout>
        <template #header>
            {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}}
        </template>

        <template #breadcrumbs>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a :href="route('users')" class="heading-font uppercase inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                        Users
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="heading-font uppercase text-sm font-medium text-gray-500 dark:text-gray-400">Profile</span>
                </div>
            </li>
        </template>

        <template #actions>
            <primary-button v-if="checkRole(user.data,'unverified') && checkRole($page.props.auth.data,'management')" @click.native="verifyDialog=true" >Verify</primary-button>
            <danger-button v-if="checkRole(user.data,'unverified') && checkRole($page.props.auth.data,'management')" @click.native="discardDialog=true">Discard</danger-button>
            <danger-button v-if="!checkRole(user.data,'unverified') && !checkRole(user.data,'disabled') && checkRole($page.props.auth.data,'management')" @click.native="disableDialog=true">Disable</danger-button>
        </template>

        <dialog-modal :show="verifyDialog" @close="verifyDialog=false">
            <template #title>
                Verify User
            </template>

            <template #content>
                Are you sure you want to verify {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}}?
            </template>

            <template #footer>
                <secondary-button @click.native="verifyDialog=false">
                    Cancel
                </secondary-button>

                 <primary-button class="ml-2" @click.native="verify" :disabled="form.processing">
                     <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                         <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                     </svg>
                     Proceed
                 </primary-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="discardDialog" @close="discardDialog=false">
            <template #title>
                Discard User
            </template>

            <template #content>
                Are you sure you want to discard {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}}?
                This user will be permanently deleted.
            </template>

            <template #footer>
                <secondary-button @click.native="discardDialog=false">
                    Cancel
                </secondary-button>

                 <danger-button class="ml-2" @click.native="discard" :disabled="form.processing">
                     <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                         <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                     </svg>
                     Proceed
                 </danger-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="disableDialog" @close="disableDialog=false">
            <template #title>
                Disable User
            </template>

            <template #content>
                Are you sure you want to disable {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}}?
                This user will no longer be able to use the system.
            </template>

            <template #footer>
                <secondary-button @click.native="disableDialog=false">
                    Cancel
                </secondary-button>

                 <danger-button class="ml-2" @click.native="disable" :disabled="form.processing">
                     <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                         <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                         <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                     </svg>
                     Proceed
                 </danger-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="giveRoleDialog" @close="giveRoleDialog=false">
            <template #title>
                Give User {{ role }} Role
            </template>

            <template #content>
                Are you sure you want to make this user {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}} act as
                {{ role }}?
            </template>

            <template #footer>
                <secondary-button @click.native="giveRoleDialog=false">
                    Cancel
                </secondary-button>

                <primary-button class="ml-2" @click.native="giveRole" :disabled="form.processing">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Proceed
                </primary-button>
            </template>
        </dialog-modal>

        <dialog-modal :show="revokeRoleDialog" @close="revokeRoleDialog=false">
            <template #title>
                Revoke {{ role }} Role from User
            </template>

            <template #content>
                Are you sure you want to revoke {{user.data.firstName}} {{user.data.middleName}} {{user.data.lastName}} of the
                {{ role }} role?
                This user will no longer act as a {{ role }} in the system.
            </template>

            <template #footer>
                <secondary-button @click.native="revokeRoleDialog=false">
                    Cancel
                </secondary-button>

                <danger-button class="ml-2" @click.native="revokeRole" :disabled="form.processing">
                    <svg v-show="form.processing" role="status" class="inline w-4 h-4 mr-3 text-white animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                    </svg>
                    Proceed
                </danger-button>
            </template>
        </dialog-modal>


        <div class="py-6">
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
                <div  v-if="!checkRole(user.data,'unverified') && checkRole($page.props.auth.data,'management')" class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Manage User Roles
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="card profile">
                           <div>
                               <!--Management-->
                               <primary-button class="m-2" v-if="!checkRole(user.data,'management') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="giveRolePrompt('management')" >Give Manager Role</primary-button>
                               <danger-button class="m-2" v-if="checkRole(user.data,'management') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="revokeRolePrompt('management')" >Revoke Manager Role</danger-button>

                               <!--Accountant-->
                               <primary-button class="m-2" v-if="!checkRole(user.data,'accountant') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="giveRolePrompt('accountant')" >Give Accountant Role</primary-button>
                               <danger-button class="m-2" v-if="checkRole(user.data,'accountant') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="revokeRolePrompt('accountant')" >Revoke Accountant Role</danger-button>

                               <!--Administration-->
                               <primary-button class="m-2" v-if="!checkRole(user.data,'administrator') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="giveRolePrompt('administrator')" >Give Administrator Role</primary-button>
                               <danger-button class="m-2" v-if="checkRole(user.data,'administrator') && checkRole($page.props.auth.data,'management') && !checkRole(user.data,'unverified') && !checkRole(user.data,'disabled')" @click.native="revokeRolePrompt('administrator')" >Revoke Administrator Role</danger-button>

                           </div>

                        </div>
                    </div>
                </div>
                <div class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Profile Information
                        </div>
                    </div>
                    <div class="page-section-content">
                        <div class="card profile">
                            <div class="p-8 md:p-10 grid grid-cols-1 sm:grid-cols-2">
                                <div class="mb-4">
                                    <div class="text-sm text-gray-600">Position</div>
                                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ user.data.position.title }}
                                    </span>
                                </div>
    <!--                                <div class="mb-4">-->
    <!--                                    <div class="text-sm text-gray-600">Grade</div>-->
    <!--                                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">-->
    <!--                                        {{ user.data.position.grade.code }}-->
    <!--                                    </span>-->
    <!--                                </div>-->
                                <div class="mb-4">
                                    <div class="text-sm text-gray-600">Email</div>
                                    <span class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase">
                                        {{ user.data.email }}
                                    </span>
                                </div>
                                <div class="mb-4">
                                    <div class="heading-font">Roles</div>
                                    <div>
                                        <span
                                            v-for="(role,index) in user.data.roles"
                                            :key="index"
                                            class="mr-2 role rounded py-1 px-2 bg-gray-200 text-gray-600 text-sm font-bold uppercase"
                                        >
                                            {{ role.name }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div v-if="!checkRole(user.data,'unverified')" class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Overview
                        </div>
                    </div>
                    <div class="page-section-content grid grid-cols-1 lg:grid-cols-2">
                        <div class="card flex justify-center">
                            <div class="h-64 w-64 sm:h-80 sm:w-80 mx-auto relative">
                                <div class="pie-chart absolute font-bold flex justify-center items-center">
                                    <div class="heading">
                                        <div class="font-bold heading-font text-center">{{ totalRequests }}</div>
                                        <div class="xl font-bold heading-font text-center">{{ totalRequests==1?'Request':'Requests' }}</div>
                                    </div>
                                </div>
                                <PieChart
                                    :chart-options="typesChartOptions"
                                    :chart-data="typesData"
                                    chart-id="types"
                                    dataset-id-key="types"
                                />
                            </div>
                        </div>
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1">
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="approvedRequestsCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((approvedRequestsCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="approvedData"
                                            chart-id="approved"
                                            dataset-id-key="approved"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Approved</div>
                                        <div class="text-sm text-gray-400">{{ approvedRequestsCount }} {{ approvedRequestsCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="pendingRequestsCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((pendingRequestsCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="pendingData"
                                            chart-id="pending"
                                            dataset-id-key="pending"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Pending</div>
                                        <div class="text-sm text-gray-400">{{ pendingRequestsCount }} {{ pendingRequestsCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="deniedRequestsCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((deniedRequestsCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="deniedData"
                                            chart-id="denied"
                                            dataset-id-key="denied"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Denied</div>
                                        <div class="text-sm text-gray-400">{{ deniedRequestsCount }} {{ deniedRequestsCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="flex justify-start items-center">
                                    <div v-if="closedRequestsCount>0" class="overview-chart relative">
                                        <div style="font-size:12px;" class="absolute h-full w-full font-bold flex justify-center items-center">
                                            {{ Math.floor((closedRequestsCount/totalRequests)*100) }}%
                                        </div>
                                        <DoughnutChart
                                            :chart-options="chartOptions"
                                            :chart-data="completedData"
                                            chart-id="complete"
                                            dataset-id-key="complete"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="heading-font" style="font-weight: 600;">Closed</div>
                                        <div class="text-sm text-gray-400">{{ closedRequestsCount }} {{ closedRequestsCount ==1?'Request':'Requests'}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!checkRole(user.data,'unverified')" class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Active Requests
                        </div>
                    </div>
                    <div class="page-section-content grid grid-cols-1 md:grid-cols-2">
                        <request
                            v-for="(request,index) in activeRequests.data"
                            :key="index"
                            :request="request"
                        />
                        <div v-if="activeRequests.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                            No Active Requests
                        </div>
                    </div>
                </div>
                <div v-if="!checkRole(user.data,'unverified')" class="page-section">
                    <div class="page-section-header">
                        <div class="page-section-title">
                            Closed Requests
                        </div>
                    </div>
                    <div class="page-section-content grid grid-cols-1 md:grid-cols-2">
                        <request
                            v-for="(request,index) in closedRequests.data"
                            :key="index"
                            :request="request"
                        />
                    </div>
                    <div v-if="closedRequests.data.length === 0" class="text-center text-gray-400 md:col-span-2 text-sm">
                        No Requests Closed
                    </div>
                    <div v-else>
                        <div class="flex flex-col items-center">
                            <!-- Help text -->
                            <span class="text-sm text-gray-700 dark:text-gray-400">
                                    Showing <span class="font-semibold text-gray-900 dark:text-white">{{ closedRequests.meta.from }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ closedRequests.meta.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ closedRequests.meta.total }}</span> Requests
                                </span>
                            <div class="inline-flex mt-2 xs:mt-0">
                                <!-- Previous Button -->
                                <a :href="closedRequests.links.prev" class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                                    Previous
                                </a>
                                <a :href="closedRequests.links.next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                                    Next
                                    <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
<!--                <div class="mt-4 flex flex-col items-center justify-start">
                    &lt;!&ndash; Help text &ndash;&gt;
                    <span class="text-sm text-gray-700 dark:text-gray-400">
                                    Showing <span class="font-semibold text-gray-900 dark:text-white">1</span> to <span class="font-semibold text-gray-900 dark:text-white">10</span> of <span class="font-semibold text-gray-900 dark:text-white">100</span> Entries
                                </span>
                    <div class="inline-flex mt-2 xs:mt-0">
                        &lt;!&ndash; Buttons &ndash;&gt;
                        <button class="inline-flex items-center py-2 px-4 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <svg class="mr-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
                            Prev
                        </button>
                        <button class="inline-flex items-center py-2 px-4 text-sm font-medium text-white bg-gray-800 rounded-r border-0 border-l border-gray-700 hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            Next
                            <svg class="ml-2 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </div>
                </div>-->
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import DoughnutChart from "@/Components/Charts/DoughnutChart";
    import PieChart from "@/Components/Charts/PieChart";
    import PrimaryButton from "@/Jetstream/Button";
    import DangerButton from "@/Jetstream/DangerButton";
    import SecondaryButton from "@/Jetstream/SecondaryButton";
    import DialogModal from "@/Jetstream/DialogModal";
    import Request from "@/Components/Request";

    export default {
        props:[
            'user',
            'totalRequests',
            'pettyCashRequestsCount',
            'requisitionRequestsCount',
            'vehicleMaintenanceRequestsCount',
            'fuelRequestsCount',
            'approvedRequestsCount',
            'pendingRequestsCount',
            'deniedRequestsCount',
            'closedRequestsCount',
            'activeRequests',
            'closedRequests',
        ],
        components: {
            AppLayout,
            DoughnutChart,
            PieChart,
            PrimaryButton,
            DangerButton,
            SecondaryButton,
            DialogModal,
            Request
        },
        data(){
          return{
              form: this.$inertia.form({

              }),
              verifyDialog:false,
              discardDialog:false,
              disableDialog:false,
              giveRoleDialog:false,
              revokeRoleDialog:false,
              role:null,
              chartOptions:{
                  plugins: {
                      tooltip: {
                          enabled: false
                      },
                      legend:{
                          display:false
                      }
                  }, cutout:20
              },
              approvedData:{
                  datasets: [{
                      data: [this.approvedRequestsCount, (this.totalRequests - this.approvedRequestsCount)],
                      backgroundColor: ['#22c55e','#e3ebf6'],
                  }],
              },
              pendingData:{
                  datasets: [{
                      data: [this.pendingRequestsCount, (this.totalRequests - this.pendingRequestsCount)],
                      backgroundColor: ['#eab308','#e3ebf6'],
                  }],
              },
              deniedData:{
                  datasets: [{
                      data: [this.deniedRequestsCount, (this.totalRequests - this.deniedRequestsCount)],
                      backgroundColor: ['#ef4444','#e3ebf6'],
                  }],
              },
              completedData:{
                  datasets: [{
                      data: [this.closedRequestsCount, (this.totalRequests - this.closedRequestsCount)],
                      backgroundColor: ['#303840','#e3ebf6'],
                  }],
              },
              typesData:{
                  datasets: [{
                      data: [
                          this.pettyCashRequestsCount,
                          this.requisitionRequestsCount,
                         // this.vehicleMaintenanceRequestsCount,
                        //  this.fuelRequestsCount
                      ],
                      backgroundColor: ['#1a56db','#ed0b4b','#b1bbc9','#e3ebf6'],
                  }],
                   labels: ['Petty Cash', 'Requisitions']
              },
              typesChartOptions:{
                  plugins: {
                      tooltip: {
                          enabled: true
                      },
                      legend:{
                          display:true,
                          position:'bottom'
                      }
                  },
                  cutout:70
              },
          }
        },

      methods:{
          verify(){
              this.form
                  .post(this.route('users.verify',{'id':this.user.data.id}),{
                      onSuccess: () =>    this.verifyDialog=false,
                  })
              // this.$inertia.post(this.route('users.verify',{'id':this.user.data.id}))
          },
          discard(){
              this.form
                  .post(this.route('users.discard',{'id':this.user.data.id}),{
                      onSuccess: () => this.discardDialog=false,
                  })
          },
          disable(){
              this.form
                  .post(this.route('users.disable',{'id':this.user.data.id}),{
                      onSuccess: () => this.disableDialog=false,
                  })
          },
          giveRolePrompt(role){
              this.role=role
              this.giveRoleDialog=true
          },
          revokeRolePrompt(role){
              this.role=role
              this.revokeRoleDialog=true
          },
          giveRole(){
              this.form
                  .post(this.route('users.give-role',{'id':this.user.data.id,'role':this.role}),{
                      onSuccess: () => this.giveRoleDialog=false,
                  })
          },
          revokeRole(){
              this.form
                  .post(this.route('users.revoke-role',{'id':this.user.data.id,'role':this.role}),{
                      onSuccess: () => this.revokeRoleDialog=false,
                  })
          },
      }
    }
</script>
