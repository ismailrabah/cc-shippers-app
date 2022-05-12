<template>
  <Head title="Edit Shipper" />

  <BreezeAuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Shipper
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
                <BreezeLabel for="company_name" value="Company Name" />
                <BreezeInput
                  id="company_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.company_name"
                  required
                  autofocus
                  autocomplete="company_name"
                />
              </div>

              <div class="mt-4">
                <BreezeLabel for="address" value="Address" />
                <BreezeInput
                  id="address"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.address"
                  required
                  autofocus
                  autocomplete="address"
                />
              </div>
              
              <Link :href="route('shippers.index')" class="p-2 bg-slate-700 rounded-md text-sm text-white hover:bg-slate-900 ">Cancel</Link>
              <BreezeButton class="mt-4 ml-2 p-2 bg-sky-500/100 rounded-md text-sm text-white hover:bg-indigo-500 " :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Update Shipper
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
export default {
  components: {
    BreezeAuthenticatedLayout,
    BreezeButton,
    BreezeInput,
    BreezeLabel,
    BreezeValidationErrors,
    Head,
    Link,
  },
  props:{
      shipper:Object
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
        company_name: this.shipper.company_name,
        address: this.shipper.address,
      }),
    };
  },
  methods: {
    submit() {
      this.form.put(this.route("shippers.update",this.shipper.id),{
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