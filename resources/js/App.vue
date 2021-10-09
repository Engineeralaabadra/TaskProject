<template>
    <div id="app">hhh</div>
</template>
<script>
export default {
    name: "App",

    components: {},

    data: () => ({
        //
    }),
    mounted() {
    console.log("all cookies : ",this.$cookies)
    this.token = this.$cookies.get("XSRF-TOKEN");
    console.log('tooo', this.token);
  axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
  console.log('tag', axios.defaults.headers.common['Authorization'])

        axios
            .post("http://127.0.0.1:8000/api/register",{

email:"sabakhbkbmjm0mn@gmail.com",
    password:"12345678",
    password_confirmation:"12345678",
    first_name:"bb",
    last_name:"vv",
    image:"jmmj",
    country:"bg",
    city:"v",
    town:"n",
    phone_no1:"jj0212060909j",
    phone_no2:"jj005006j0000",
    reg_info:"111"
            },{
              headers:{
              '_token': this.token

            }
            }
            )
            .then((res) => {
                console.log(res.data);
            });
    },


};
</script>
