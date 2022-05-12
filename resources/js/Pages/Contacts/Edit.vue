<template>
  <Head title="Edit Contact" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Contact
      </h2>
    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <BreezeValidationErrors class="mb-4" />
            <form @submit.prevent="submit">
              <input type="hidden" name="csrf-token" value="csrf" />
              <div class="mt-4">
                <BreezeLabel for="name" value="Name" />
                <BreezeInput
                  id="name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.name"
                  required
                  autofocus
                  autocomplete="name"
                />
              </div>

              <div class="mt-4">
                <BreezeLabel for="number" value="Number" />
                <BreezeInput
                  id="number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.number"
                  required
                  autofocus
                  autocomplete="number"
                />
              </div>
              
              <div class="mt-4">
                <BreezeLabel for="number" value="Number" />
                <BreezeCheckbox id="is_primary" name="is_primary" v-model:checked="form.is_primary" @update:checked="toggerPrimary(form.id)" />
              </div>
              
              <Link :href="route('shippers.index')" class="p-2 bg-slate-700 rounded-md text-sm text-white hover:bg-slate-900 ">Cancel</Link>
              <BreezeButton class="mt-4 ml-2 p-2 bg-sky-500/100 rounded-md text-sm text-white hover:bg-indigo-500 " :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Update Contact
              </BreezeButton>
            </form>
          </div>
        </div>
      </div>
    </div>
  </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { Link } from "@inertiajs/inertia-vue3";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import BreezeCheckbox from '@/Components/Checkbox.vue';
export default {
  components: {
    BreezeAuthenticatedLayout,
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    BreezeValidationErrors,
    Head,
    Link,
    BreezeCheckbox
  },
  props:{
      contact:Object
  },
  computed: {
      flash() {
          return this.$page.props.flash || {}
      }
  },
  data() {
    return {
      csrf: document.head.querySelector('meta[name="csrf-token"]')
        ? document.head.querySelector('meta[name="csrf-token"]').content
        : "",
      form: this.$inertia.form({
        _token: document.head.querySelector('meta[name="csrf-token"]')
          ? document.head.querySelector('meta[name="csrf-token"]').content
          : "",
        name: this.contact.name,
        number: this.contact.number,
        is_primary: this.contact.is_primary,
      }),
    };
  },
  methods: {
    submit() {
      this.form.put(this.route("contacts.update",this.contact.id),{
          onSuccess: res => {
              if (this.flash.success) {
                this.$inertia.visit(route('admin.sales.index'));
              } else if (this.flash.error) {
                // this.$page.props.flash.notification.add('error',this.flash.error);
              } else {
                // this.$page.props.flash.notification.add('error',"Unknown server error.");
              }
          },
          onError: res => {
              console.log(res)
              // this.$page.props.flash.notification.add('error',"A server error occurred");
          }
      },{remember: false, preserveState: true});
    },
  },
};
</script>