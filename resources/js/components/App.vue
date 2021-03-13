
<template>
  <div id="app">
    <div class="heading" align="center">
      <h1 align="left">Blog Post Comments - Jogie Lustre</h1>
      <img src="images/istockphoto.jpg" >
    </div>
    <div class="container" align="center">
        <br/>
        <hr color="blue"/>
                    <div class="row w-50 align-items-center">
                        <div class="col-2"> <h5> Comment: </h5></div> 
                        <div class="col-3">
                            <input  class="form-control mb-2" name="name"  placeholder="Name" v-model="cform.name">
                        </div>
                        <div class="col-4">
                            <input  class="form-control mb-2" name="message"  placeholder="Comment" v-model="cform.message">
                        </div>
                        <div class="col-3">
                            <button class="btn btn-primary mb-2" @click="create()">Post</button>
                        </div>
                    </div>
        <hr color="blue"/>
        <table>
            <tbody>
                <tr v-for="item in commentList" key="item.id">
                    <td>  
                        <table class="table table-hover" >
                            <tr>
                                    <td>
                                        <div class="form-row align-items-center">
                                            <span class="badge badge-light">{{ properCase(item.name)}}:</span>
                                            <v-chip
                                                class="ma-2"
                                                color="#FFA62F"
                                                text-color="white"
                                                :label="item.message"
                                                >
                                            </v-chip>
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <div class="container">
                                    <CommentComponent :id="item.id"/>
                                </div>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</template>

<script>
  import CommentComponent from './CommentComponent.vue';
  import Vue from 'vue'
  import VChip from 'v-chip'
 
  Vue.use(VChip)
  export default {
    components: { CommentComponent },
    data() {
      return {
        commentList: '',
        cform:{
            name:'',
            message:'',
            parentcommentid:0,
        },
        reply:{},
        replyName:{},
        replyMessage:{},
      }
    },
    methods: {
      fetchData() {
        const here = this;
           // const { data } = window.axios.get('/api/comments');
           // data.forEach(comment => this.comments.push(new Comment(comment)));
        axios.get('/comments/0')
         .then(function (response) {
             console.log(response.data)
             here.commentList = response.data.data;
         })
         .catch(function (error) {
             alert(error);

         });
        },
      create(itemKey=null) {
          const here = this;
          console.log(here.cform)

          let cur_parentcommentid = 0;
          let cur_name = '';
          let cur_message = '';

          if(itemKey!=null){
              cur_parentcommentid=itemKey;
              cur_name = here.replyName[itemKey];
              cur_message = here.replyMessage[itemKey];
          }else{
              cur_name = here.cform.name,
              cur_message = here.cform.message,
              cur_parentcommentid = here.cform.parentcommentid
          }

          
          axios.post('/comments/store', {
              name: cur_name,
              message: cur_message,
              parentcommentid: cur_parentcommentid
            })
         .then(function (response) {
             here.commentList.unshift(response.data.data);
             
             if(itemKey!=null){
                here.replyName[itemKey] = '';
                here.replyMessage[itemKey]=''
             }else{
                 here.cform.name = '';
                here.cform.message = '';
                here.cform.parentcommentid = 0;
             }

             alert('Success!');
         })
         .catch(function (error) {
             alert('Something went wrong!');

         });
          
      },
      properCase(name) {
        return name.charAt(0).toUpperCase() + name.slice(1);
      },
      
    },
    created(){
        this.fetchData();
    }
    
  }
</script>
<style>
.container{
    top:10px;
    align-items: center;
}
</style>