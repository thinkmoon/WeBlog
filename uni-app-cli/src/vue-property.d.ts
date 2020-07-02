import Vue from "vue";
import { Moment } from "moment";
declare module "vue/types/vue" {
  interface Vue {
    $api: any;
    $moment: Moment;
  }
}
