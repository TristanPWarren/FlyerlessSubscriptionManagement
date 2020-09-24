<template>
    <div>
        <div
            id="instruction-message"
            v-model="userName"
        >
            Hi {{userName}}, You can download all of the emails from the members of your society from here.
        </div>

        <div v-if="canDownload">
            <b-button type="submit" variant="primary" :disabled="this.listOfSocieties.length === 0">Update</b-button>
        </div>
        <div v-else>
            <div id="authentication-warning"> Use as an authorised user to modify club details </div>
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
                userName: '',
                listOfSocieties: [],
            }
        },


        mounted() {
            this.loadSocieties();
        },

        methods: {
            loadSocieties() {
                this.listOfSocieties = [];
                this.$http.get('user_societies')
                    .then((response) => {
                        this.userName = response.data.user_name;
                        for (let society of response.data.societies) {
                            if (society.interest === true) {
                                this.listOfSocieties.push(society);
                            }
                        }
                    })
                    .catch(error => this.$notify.alert('Sorry, something went wrong retrieving list of societies: ' + error.message));
            },

            submit() {
                let formData = new FormData();

                formData.append('societies', JSON.stringify(this.listOfSocieties));

                this.$http.post('user_societies', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                        this.$notify.success('Society preferences updated!');
                        this.loadSocieties();
                    })
                    .catch(error => this.$notify.alert('There was a problem updating your preferences: ' + error.message));
            },

            reset() {

            },

        },

        computed: {}
    }
</script>

<style scoped>
#instruction-message {
    margin-top: 20px;
    margin-bottom: 15px;
    text-align: center;
}

#column-headers {
    display: flex;
    width: 80%;
    justify-content: space-between;
}

#button-group-titles {
    display: flex;
    width: 120px;
    justify-content: space-between;
}

#line-break {
    height: 2px;
    background-color: #c7c7c7;
    width: 90%;
    margin-bottom: 10px;
}

#society-main-container {
    display: flex;
    flex-direction: column;
    align-items : center;
    width: 80%;
    margin-bottom: 25px;
}

.society-line {
    display: flex;
    width: 80%;
    justify-content: space-between;
}

.society-name {
    width: calc(100% - 130px);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.button-group {
    width: 100px;
    display: flex;
    justify-content: space-between;
}

#no-societies-message {
    text-align: center;
    height: 20px;
    width: 100%;
    margin-bottom: 50px;
}

</style>