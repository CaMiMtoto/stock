import axios from 'axios';
const client = axios.create({
    baseURL: '/api',
});

export default {
    all() {
        return client.get('menus');
    },
    find(id) {
        return client.get(`menus/${id}`);
    },
    update(id, data) {
        return client.put(`menus/${id}`, data);
    },
    delete(id) {
        return client.delete(`menus/${id}`);
    },
};