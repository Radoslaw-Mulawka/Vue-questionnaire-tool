<template>
    <div class="app-container">
        <div class="filter-container">
            <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
            <el-select v-model="query.role" :placeholder="$t('table.role')" clearable style="width: 90px" class="filter-item" @change="handleFilter">
                <el-option v-for="item in roles" :key="item" :label="item | uppercaseFirst" :value="item" />
            </el-select>
            <el-button v-waves class="filter-item" type="primary" @click="handleFilter">
                Szukaj
            </el-button>
        </div>

        <el-table v-loading="loading" :data="list" style="width: 100%" class="users-list-table">
            <el-table-column align="center" label="ID" width="80">
                <template slot-scope="scope">
                    <span>{{ scope.row.index }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Imie">
                <template slot-scope="scope">
                    <span>{{ scope.row.firstName }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Nazwisko">
                <template slot-scope="scope">
                    <span>{{ scope.row.lastName }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Email">
                <template slot-scope="scope">
                    <span>{{ scope.row.email }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Kampanie" width="120">
                <template slot-scope="scope">
                    <span>{{ scope.row.campaigns }}</span>
                </template>
            </el-table-column>

            <el-table-column align="center" label="Rola" width="120">
                <template slot-scope="scope">
                    <span>{{ scope.row.roles.join(', ') }}</span>
                </template>
            </el-table-column>
        </el-table>

        <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
    </div>
</template>
<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import UserResource from '@/api/user';
import Resource from '@/api/resource';
import waves from '@/directive/waves'; // Waves directive
// import permission from '@/directive/permission'; // Waves directive
import checkPermission from '@/utils/permission'; // Permission checking

const userResource = new UserResource();
const permissionResource = new Resource('permissions');

export default {
  name: 'UserList',
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      list: null,
      total: 0,
      loading: true,
      query: {
        page: 1,
        limit: 15,
        keyword: '',
        role: '',
      },
      roles: ['admin', 'user'],
    };
  },
  created() {
    this.getList();
    if (checkPermission(['manage permission'])) {
      this.getPermissions();
    }
  },
  methods: {
    checkPermission,
    async getPermissions() {
      const { data } = await permissionResource.list({});
      const { all, menu, other } = this.classifyPermissions(data);
      this.permissions = all;
      this.menuPermissions = menu;
      this.otherPermissions = other;
    },

    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await userResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
    classifyPermissions(permissions) {
      const all = []; const menu = []; const other = [];
      permissions.forEach(permission => {
        const permissionName = permission.name;
        all.push(permission);
        if (permissionName.startsWith('view menu')) {
          menu.push(this.normalizeMenuPermission(permission));
        } else {
          other.push(this.normalizePermission(permission));
        }
      });
      return { all, menu, other };
    },

    normalizeMenuPermission(permission) {
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name.substring(10)), disabled: permission.disabled || false };
    },
    normalizePermission(permission) {
      const disabled = permission.disabled || permission.name === 'manage permission';
      return { id: permission.id, name: this.$options.filters.uppercaseFirst(permission.name), disabled: disabled };
    },
  },
};
</script>

<style lang="scss">
    .app-container {
        padding: 0;
        .filter-container{
            button{
                background-color: #6161F5;
                border: none;
                padding: 10px 30px;
                border-radius: 20px;
                color: #E4E9EF;
                cursor: pointer;
                margin-left: 20px;
            }
        }
        .users-list-table{
            width: 100%;
            box-shadow: 0 2px 4px 0 rgba(0,0,0,.19);
            border-radius: 3px;
            color: #000000;
            table>thead>tr{
                background-color: #fafafd !important;
                height:70px;
                color: #000000;
            }
            table>thead>tr>th{
                background-color: #fafafd !important;
                border-bottom: 1px solid #cbcbcb !important;
                font-size: 16px;
                font-weight: bold;
            }
            thead>tr>th>.cell{
                text-align: left;
            }
            thead>tr>th:first-child{
                padding-left: 10px;
            }
            /*tbody>.el-table__row {*/
            /*    &:hover{*/
            /*        !*background-color: initial !important;   *!*/
            /*        background-color: red !important;*/
            /*    }*/
            /*}*/
            tbody>tr>td{
                border-bottom: 1px solid #cbcbcb !important;
            }
            tbody>tr>td:first-child{
                padding-left: 10px;
            }
            tbody>tr>td>.cell{
                text-align: left;
            }
        }
        .pagination-container{
            border-radius: 3px;
            box-shadow: 0 2px 4px 0 rgba(0,0,0,.19);
            .el-pagination.is-background .el-pager li:not(.disabled).active {
                background-color: #6161F5;
            }
        }
        .el-table--enable-row-hover .el-table__body tr:hover>td{
            background-color: #ffffff !important;
        }
    }
</style>
