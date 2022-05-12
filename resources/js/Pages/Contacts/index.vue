<template>
    <div>

        <div class="p-6 bg-white mx-auto border-b border-gray-200">
            <table class="table-auto mt-4" style="width: 100%">
              <thead class="table-header-group">
                <tr class="text-md bg-gray-800 text-white p-8 border shadow">
                  <!-- <th class="px-4 border">#</th> -->
                  <th class="px-4 border">Name</th>
                  <th class="px-4 border">Number</th>
                  <th class="px-4 border">Primary</th>
                  <th class="px-4 border">Edit</th>
                  <th class="px-4 border">Delete</th>
                </tr>
              </thead>
              <tbody class="">
                <tr v-if="contacts.data == ''">
                  <td class=" text-center" colspan="8">
                    No contacts Found!
                  </td>
                </tr>
                <tr v-else v-for="contact in contacts.data" :key="contact.id">
                    <!-- <td class="p-2">{{ contact.id }}</td> -->
                    <td class="p-2">{{ contact.name }}</td>
                    <td class="p-2">{{ contact.number }}</td>
                    <td class="p-2">
                         <form @submit.prevent="toggerPrimary(contact.id)">
                            <BreezeCheckbox name="is_primary" v-model:checked="contact.is_primary" @update:checked="toggerPrimary(contact.id)" />
                        </form>
                    </td>
                    <td class="p-2">
                        <BreezeButton @click="edit(contact.id)" class="bg-green-600 shadow">
                            Edit
                        </BreezeButton>
                    </td> 
                    <td>
                        <form @submit.prevent="destroy(contact)">
                            <BreezeButton class="bg-rose-600" type="submit">
                                Delete
                            </BreezeButton>
                        </form>
                    </td>
                </tr>
              </tbody>
            </table>
            <!-- <pagination class="mt-6" :links="contacts.links" /> -->
          </div>

            <div class="flex gap-x-2">
                <Link :href="route('contacts.create' , shipper_id )" class="p-2 bg-sky-500/100 rounded-md text-sm text-white hover:bg-sky-500 "> Add Contact </Link>
            </div>
    </div>

</template>

<script>
    import { defineComponent } from "vue";
    import BreezeCheckbox from '@/Components/Checkbox.vue';
    import { Head } from "@inertiajs/inertia-vue3";
    import BreezeInput from "@/Components/Input.vue";
    import { Link } from "@inertiajs/inertia-vue3";
    import BreezeButton from "@/Components/Button.vue";
    import Pagination from "@/Components/Pagination.vue";
    import axios from "axios";
import { isTemplateNode } from "@vue/compiler-core";

    export default defineComponent({
        name: "ShipperContacts",
        components: {
            BreezeCheckbox,
            BreezeButton,
            Head,
            Link,
            BreezeInput,
            Pagination,
        },
        props: {
            shipper_id:Object,
            contacts:Object,
        },
        data() {
            return {
            }
        },
        mounted() {
        },
        computed: {
            flash() {
                return this.$page.props.notifications || {}
            }
        },
        methods: {
            edit(iid) {
                this.$inertia.get(this.route("contacts.edit", iid));
            },
            destroy(contact) {
                ;
                if (confirm("Are You Sure!")) {
                    this.$inertia.delete(route("contacts.destroy", contact.id),{
                        onFinish: res => {
                            this.contacts.data.splice(this.contacts.data.indexOf(contact), 1);
                        },
                        onError: err => {
                            console.log(err);
                            this.displayNotification('error', "There was an error while deleting the item.");
                        }
                    });
                }
            },
            toggerPrimary(id){
                axios.put(route('contacts.toggle_primary',{'contact_id': id })).then(res => {
                    this.contacts.data.map(function(item){
                        if(item.id != id){
                            item.is_primary = false;
                        }
                    });
                })
            }
        }
    });
</script>

<style scoped>

</style>
