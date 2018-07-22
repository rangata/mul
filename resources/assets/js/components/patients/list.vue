<template>
    <div >
        <div class="table-responsive">
            <vue-good-table :rows="rows" :columns="columns" :pagination-options="{
                    enabled: true,
                    perPage: 10,
                    position: 'top',
                    perPageDropdown: [10, 20, 50],
                    nextLabel: 'Следваща',
                    prevLabel: 'Предишна',
                    rowsPerPageLabel: 'Редове на страница',
                    ofLabel: 'от',
                    pageLabel: 'страница', // for 'pages' mode
                    allLabel: 'Всички'
  }"
                            :search-options="{
                    enabled: true,
                    placeholder: 'Търсене...',
                  }"
            ></vue-good-table>
        </div>
    </div>
</template>

<script>
    import VueGoodTable from 'vue-good-table';

    export default {
        name: "list",
        props: ['table-data'],
        data() {
            return {
                columns: [
                    {
                        label: 'Име и Фамилия на пациента',
                        filterable: true,
                        field: this.name
                    },
                    {
                        label: 'Рожденна дата/ЕГН',
                        field: this.birthday,
                        filterable: true
                    },
                    {
                        label: 'more',
                        html: true,
                        field: this.bts
                    }
                ],
                rows: []
            }
        },
        mounted() {

            this.rows = this.$props.tableData
console.log(this.rows[0])
        },
        methods: {
            name(val) {
                return val['tags'][0].value
            },
            birthday(val) {
                return val['tags'][1].value
            },
            bts(val) {
                var buttons =
                    '<div class="btn-group">'+
                    '<a class="btn btn-info" href=' + val.publicId + '>Отвори този пациент</a>' +
                    '<a class="btn btn-success" href=' + val.publicId + '>Генерирай код</a>' +
                    '</div>'

                return buttons
            }
        }
    }
</script>

<style scoped>

</style>