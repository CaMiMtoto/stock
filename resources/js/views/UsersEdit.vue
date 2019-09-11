<template>
    <div class="col-sm-6 offset-sm-3">
        <h2 class="text-center">User details</h2>

        <div v-if="message"  class="alert alert-danger" role="alert">
            {{ message }}
        </div>
        <div v-if="! loaded">Loading...</div>
        <form @submit.prevent="onSubmit($event)" v-else>

            <div class="form-group row">
                <label for="user_name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" v-model="user.name" class="form-control" id="user_name" placeholder="Full name">
                </div>
            </div>

            <div class="form-group row">
                <label for="user_email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" v-model="user.email" class="form-control" id="user_email" placeholder="Full name">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                    <button type="submit" class="btn btn-primary"
                            :disabled="saving">Update</button>
                    <button class="btn btn-danger"
                            :disabled="saving" @click.prevent="onDelete($event)">Delete</button>
                    <button class="btn btn-secondary"
                            type="button" v-on:click="$router.back()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    import api from '../api/menus';

    export default {
        data() {
            return {
                message: null,
                loaded: false,
                saving: false,
                user: {
                    id: null,
                    name: "",
                    email: ""
                }
            };
        },
        methods: {
            onSubmit(event) {
                this.saving = true;
                api.update(this.user.id, {
                    name: this.user.name,
                    email: this.user.email,
                }).then((response) => {
                    this.message = 'User updated';
                    this.user = response.data.data;
                    setTimeout(() => this.message = null, 2000);
                }).catch(error => {
                }).then(_ => this.saving = false);
            },
            onDelete() {
                this.saving = true;
                api.delete(this.user.id)
                    .then((response) => {
                        this.message = 'User Deleted';
                        setTimeout(() => this.$router.push({ name: 'users.index' }), 2000);
                    });
            }
        },
        created() {
            api.find(this.$route.params.id).then((response) => {
                this.loaded = true;
                this.user = response.data.data;
            }).catch((err) => {
                this.$router.push({ name: '404' });
            });
        }
    };
</script>