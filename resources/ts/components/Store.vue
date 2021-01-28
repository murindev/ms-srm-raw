<template>
  <div>
    <h1>Store Module Foo</h1>

    <h4>Text from State</h4>
    <hr> text w
    {{ text }}
    <br>
    <br>
    <br>
    <br>

    <h4>Text from State Getter  {{showSnippet()}} 7777777</h4>
    <hr>
<!--    {{ textUpper }}-->
    <br>
    <br>
    <br>
    <br>

    <h4>Change Text Inside Module</h4>
    <hr>
    <input
            type="text"
            :value="text"
            @input="changeText"
    >
    <br>
    <br>
    <br>
    <br>
  </div>
</template>

<script lang="ts">
  import { Component, Vue } from 'vue-property-decorator'
  import { namespace } from 'vuex-class'
  import * as moment from 'moment';
  const fooModule = namespace('Food')

  @Component
  export default class Store extends Vue {
    @fooModule.State
    private text!: string
    // @fooModule.Getter
    // private textUpper!: string
    @fooModule.Mutation
    private setText!: (newText: string) => void

    // changeText (event: any): void {
    //   this.setText(event.target.value)
    // }
    // showSnippet () : any {
    //   return moment().format('YYYY-MM-DD')
    // }
  }
</script>
