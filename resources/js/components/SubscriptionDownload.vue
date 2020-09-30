<template>
    <div id="admin-main-container">
        <div v-if="canDownload">
            <b-button
                id="download-button"
                variant="primary"
                @click="getMemberDetails"
            > Download </b-button>
        </div>
        <div v-else>
            <div id="authentication-warning"> Use as an authorised user to download members details </div>
        </div>

    </div>
</template>


<script>

    export default {
        name: "SubscriptionManagementForm",

        props: {
            canDownload: {
                type: Boolean,
                required: true,
                default: false,
            },
            queryString: {
                type: String,
                required: true,
            },
        },

        data() {
            return {

            }
        },


        mounted() {
        },

        methods: {
            getMemberDetails() {
                this.$http.get('member_details')
                    .then((response) => {
                        let csvContent = "data:text/csv;charset=utf-8,";
                        response.data.forEach(function(rowArray) {
                            let row = rowArray.join(",");
                            csvContent += row + "\r\n";
                        });

                        var d = new Date();
                        var dd = String(d.getDate()).padStart(2, '0');
                        var mm = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = d.getFullYear();
                        let today = mm + '_' + dd + '_' + yyyy;

                        let encodedUri = encodeURI(csvContent);
                        let link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", `members_${today}.csv`);
                        document.body.appendChild(link); // Required for FF
                        link.click();
                    })
                    .catch(error => this.$notify.alert('Sorry, something went wrong retrieving membership details: ' + error.message));
            },
        },

        computed: {}
    }
</script>

<style scoped>

#admin-main-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

#download-button {
    margin-top: 40px;
}


</style>