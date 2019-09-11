
<template>
    <div class="users">

        <div v-if="error" class="error">
            <p>{{ error }}</p>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Manager users</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover table-striped table-borderless" v-if="users">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="{ id, name, email } in users">
                        <td>{{ name }}</td>
                        <td>{{ email }}</td>
                        <td>
                            <router-link class="btn btn-primary btn-sm" :to="{ name: 'users.edit', params: { id } }">Edit</router-link>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{disabled :! prevPage}">
                            <a class="page-link"  @click.prevent="goToPrev">Previous</a>
                        </li>
                        <li class="page-item active">
                    <span class="page-link">
                    {{ paginatonCount }}
                    <span class="sr-only">(current)</span>
                </span>
                        </li>
                        <li class="page-item"  :class="{disabled :! nextPage}">
                            <a class="page-link" @click.prevent="goToNext">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>


    </div>
</template>


<script>
    import axios from 'axios';
    const getUsers = (page, callback) => {
        const params = { page };

        axios.get('/api/menus', { params })
            .then(response => {
                callback(null, response.data);
            }).catch(error => {
            callback(error, error.response.data);
        });
    };

    export default {
        data() {
            return {
                users: null,
                meta: null,
                links: {
                    first: null,
                    last: null,
                    next: null,
                    prev: null,
                },
                error: null,
            };
        },
        computed: {
            nextPage() {
                if (! this.meta || this.meta.current_page === this.meta.last_page) {
                    return;
                }

                return this.meta.current_page + 1;
            },
            prevPage() {
                if (! this.meta || this.meta.current_page === 1) {
                    return;
                }

                return this.meta.current_page - 1;
            },
            paginatonCount() {
                if (! this.meta) {
                    return;
                }

                const { current_page, last_page } = this.meta;

                return `${current_page} of ${last_page}`;
            },
        },
        beforeRouteEnter (to, from, next) {
            getUsers(to.query.page, (err, data) => {
                next(vm => vm.setData(err, data));
            });
        },
        // when route changes and this component is already rendered,
        // the logic will be slightly different.
        beforeRouteUpdate (to, from, next) {
            this.users = this.links = this.meta = null;
            getUsers(to.query.page, (err, data) => {
                this.setData(err, data);
                next();
            });
        },
        methods: {
            goToNext() {
                this.$router.push({
                    query: {
                        page: this.nextPage,
                    },
                });
            },
            goToPrev() {
                this.$router.push({
                    name: 'menus.index',
                    query: {
                        page: this.prevPage,
                    }
                });
            },
            setData(err, { data: users, links, meta }) {
                if (err) {
                    this.error = err.toString();
                } else {
                    this.users = users;
                    this.links = links;
                    this.meta = meta;
                }
            },
        }
    }
</script>