<template>
  <div class="comment" >

                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <input  class="form-control mb-2" name="name"  placeholder="Name" v-model="cform.name">
                        </div>
                        <div class="col-auto">
                            <input  class="form-control mb-2" name="message"  placeholder="Comment" v-model="cform.message">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary mb-2" @click="create()">Reply</button>
                        </div>
                    </div>

    <table>
        <tbody>
            <tr v-for="item in commentList" key="item.id">
                <td>  
                    <table class="table table-hover">
                        <tr>
                                <td>
                                      <div class="form-row align-items-center">
                                            <span class="badge badge-light">{{ properCase(item.name)}}:</span>
                                            <v-chip
                                                class="ma-2"
                                                color="#387C44"
                                                text-color="white"
                                                :label="item.message"
                                                >
                                            </v-chip>
                                        </div>
                                </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">
                                    <CommentComponentLast :id="item.id"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    
  </div>
</template>
<script>
import CommentComponentLast from './CommentComponentLast.vue';
  export default {
    components: { CommentComponentLast },
    props: ['id'],
    data(){
        return{
            commentList: '',
            cform:{
                name:'',
                message:'',
                parentcommentid:this.id,
            },
        }
    },
    
    methods: {
       fetchData(id) {
        const here = this;
        
        axios.get('/comments/'+id)
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
                  here.cform.parentcommentid = here.id;
              }
                         

             alert('Success!');
         })
         .catch(function (error) {
             alert('Something went wrong!');

         });
        },
        properCase(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    },
     created() {
        const here = this
        here.fetchData(here.id) 
    },
  }
</script>
<style>
</style>