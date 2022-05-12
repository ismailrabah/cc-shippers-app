<template>
  <Head title="Dashboard" />

  <BreezeAuthenticatedLayout>
    <template #header>
        <div class="flex flex-wrap items-center justify-between w-full px-4">
            <Link :href="route('dashboard')" class="text-sm font-black text-gray"> 	&#8592; Back </Link>
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Shippers List
            </h2>

            <div class="flex gap-x-2">
                <Link :href="route('shippers.create')" class="p-2 bg-sky-500/100 rounded-md text-sm text-white hover:bg-sky-500 "> New Shipper </Link>
            </div>
        </div>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white mx-auto border-b border-gray-200">
            <table class="table-auto mt-4" style="width: 100%">
              <thead class="table-header-group">
                <tr class="text-sm bg-gray-800 text-white p-8 border shadow">
                  <th class="px-4 border">#</th>
                  <th class="px-4 border">Company Name</th>
                  <th class="px-4 border">Address</th>
                  <th class="px-4 border">Contact Number</th>
                  <th class="px-4 border">Contact Name</th>
                  <th class="px-4 border">Created At</th>
                  <th class="px-4 border">Contacts</th>
                  <th class="px-4 border">Edit</th>
                  <th class="px-4 border">Delete</th>
                </tr>
              </thead>
              <tbody class="">
                <tr v-if="shippers.data == ''">
                  <td class=" text-center" colspan="8">
                    No Shippers Found!
                  </td>
                </tr>
                <tr v-else v-for="shipper in shippers.data" :key="shipper.id">
                    <td class="p-2">{{ shipper.id }}</td>
                    <td class="p-2">{{ shipper.company_name }}</td>
                    <td class="p-2">{{ shipper.address }}</td>
                    <td class="p-2">
                        {{ shipper.contact ? shipper.contact.number : "" }}
                    </td>
                    <td class="p-2">
                        {{ shipper.contact ? shipper.contact.name : "" }}
                    </td>
                    <td class="p-2">{{ shipper.created_at }}</td>
                     <td class="p-2">
                        <BreezeButton @click="contacts(shipper)" class="bg-indigo-500 shadow">
                            Contacts
                        </BreezeButton>
                    </td>
                    <td class="p-2">
                        <BreezeButton @click="edit(shipper.id)" class="bg-green-600 shadow">
                            Edit
                        </BreezeButton>
                    </td> 
                    <td>
                        <form @submit.prevent="destroy(shipper.id)">
                            <BreezeButton class="bg-rose-600" type="submit">
                                Delete
                            </BreezeButton>
                        </form>
                    </td>
                </tr>
              </tbody>
            </table>
            <pagination class="mt-6" :links="shippers.links" />
          </div>
        </div>
      </div>
    </div>
    
    <!-- Company Contact list modal -->
    <vue-final-modal v-model="contactModal" @closed="contactModalClosed" :drag="true" :resize="true"   v-if="selectedShipper"
      :resize-directions="['t', 'tr', 'r', 'br', 'b', 'bl', 'l', 'tl']" classes="modal-container" content-class="modal-content">
        <button class="modal__close" @click="contactModal = false">
            <mdi-close>X</mdi-close>
        </button>
        <span class="modal__title">Contacts of {{selectedShipper.company_name}}</span>
        <div class="modal__content">
            <shipper-contacts :contacts="selectedShipperContacts" :shipper_id="selectedShipper.id" @defaultChanged="onDefaultChanged" @error="onContactsError" ></shipper-contacts>
        </div>
    </vue-final-modal>
  </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import BreezeInput from "@/Components/Input.vue";
import { Link } from "@inertiajs/inertia-vue3";
import BreezeButton from "@/Components/Button.vue";
import Pagination from "@/Components/Pagination.vue";
import axios from "axios";
import { $vfm, VueFinalModal, ModalsContainer } from 'vue-final-modal'
import ShipperContacts from "@/Pages/Contacts/index.vue"


export default {
    props: {
        shippers: Object,
    },
    components: {
        BreezeAuthenticatedLayout,
        BreezeButton,
        Head,
        Link,
        BreezeInput,
        Pagination,
        VueFinalModal,
        ModalsContainer,
        ShipperContacts
    },data() {
      return {
        contactModal : false,
        selectedShipper : null,
        selectedShipperContacts:[]
      }
    },
    methods: {
        edit(iid) {
            this.$inertia.get(this.route("shippers.edit", iid));
        },
        destroy(id) {
            if (confirm("Are You Sure!")) {
                this.$inertia.delete(this.route("shippers.delete", id));
            }
        },
        getContacts(shipper){
            this.selectedShipper = shipper;
            axios.get(route('contacts.index',{'shipper_id': shipper.id })).then(res => {
                this.selectedShipperContacts = res.data.payload;
            })
        },
        contacts(shipper){
            // SHOW CONTACT LIST MODAL
            this.getContacts(shipper);
            this.contactModal = true;
        },
        onDefaultChanged(shipper , default_conatct){
          console.log(shipper , default_conatct);
          this.contactModal = false;

        },
        onContactsError(msg){
          console.log(msg);
          this.contactModal = false;
        },
        contactModalClosed(){
          //do nothing
        }
    },
};
</script>
<style scoped>
    ::v-deep .modal-container {
    display: flex;
    justify-content: center;
    align-items: center;
    }
    ::v-deep .modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    max-height: 90%;
    margin: 0 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    background: #fff;
    }
    .modal__title {
      margin: 0 2rem 0 0;
      font-size: 1.5rem;
      font-weight: 700;
    }
    .modal__content {
    flex-grow: 1;
    overflow-y: auto;
    }
    .modal__action {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-shrink: 0;
    padding: 1rem 0 0;
    }
    .modal__close {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    }
    .dark-mode div::v-deep .modal-content {
        border-color: #2d3748;
        background-color: #1a202c;
    }
</style>