<template>
    <div>
        <div class="filter-container">
            <el-input v-model="query.keyword" :placeholder="$t('table.keyword')" style="width: 200px;" class="filter-item" @keyup.enter.native="handleFilter" />
            <el-button v-waves class="filter-item" type="primary" @click="handleFilter">
                Szukaj
            </el-button>
        </div>
        <table class="campaigns-list-table">
            <thead class="thead-default">
                <tr>
                    <th scope="col" class="name">Nazwa</th>
                    <th scope="col" class="status">Status</th>
                    <th scope="col" class="data">Data wyświetlania</th>
                    <th scope="col" class="action">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <CampaignsTableLine v-for='campaign in campaignsList' :key='campaign.id' :campaign='campaign'></CampaignsTableLine> <!-- $store.campaignsListData' -->
            </tbody>
        </table>
        <pagination v-show="total>0" :total="total" :page.sync="query.page" :limit.sync="query.limit" @pagination="getList" />
    </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import CampaignsTableLine from './CampaignsTableLine.vue';
import waves from '@/directive/waves';
import Campaigns from '@/api/campaigns'; // Waves directive
const campaignResource = new Campaigns('campaigns');
export default {
  components: {
    CampaignsTableLine,
    Pagination,
  },
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
    };
  },
  created() {
    this.getList();
    this.$store.dispatch('campaign/getCampaignsList');
  }, methods: {
    async getList() {
      const { limit, page } = this.query;
      this.loading = true;
      const { data, meta } = await campaignResource.list(this.query);
      this.list = data;
      this.list.forEach((element, index) => {
        element['index'] = (page - 1) * limit + index + 1;
      });
      this.$store.dispatch('campaign/setCampaignList', data);
      this.total = meta.total;
      this.loading = false;
    },
    handleFilter() {
      this.query.page = 1;
      this.getList();
    },
  },
  computed: {
    campaignsList: {
      set(value) {
        this.$store.dispatch('campaign/setCampaignList', value);
      },
      get() {
        return this.$store.getters.getCampaignsList;
      },
    },
  },
};
</script>

<style lang="scss">
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
    .campaigns-list-table {
        width: 100%;
        box-shadow: 0 2px 4px 0 rgba(0,0,0,.19);
        background-color: #fafafd;
        text-align:left;
        border-collapse: collapse;
        border-radius: 3px;

        table-layout: fixed;

        tbody{
            font-size:14px;
        }
        tr{
            border-bottom: 1px solid #cbcbcb;
            background-color: white;
        }
        thead>tr{
            background-color: #fafafd;
            height:70px;
        }
        td, th {
            padding-top:10px;
            padding-bottom:10px;
            padding-right:20px;
        }

        td:first-child, th:first-child {
            padding-left:20px;
            padding-right:0;
        }
        a{
            color: #1717f2;
            &:hover {
                text-decoration: underline;
            }
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
    @media screen and (max-width: 600px) {
            table.campaigns-list-table {
                border: 0;
                box-shadow: none;
                background-color: #e4e9ef;
            }

            table caption {
                font-size: 1.3em;
            }
            table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
            }
            table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .8em;
                text-align: right;
            }
            table td::before {
                /*
                * aria-label has no advantage, it won't be read inside a table
                content: attr(aria-label);
                */
                content: attr(data-th);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
            }
            table td:last-child {
                border-bottom: 0;
            }
            td, th {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
        }
</style>
