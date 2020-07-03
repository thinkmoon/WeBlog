import Vue from "vue";
import { Moment } from "moment";
import Api from "./plugins/fly/api";
declare module "vue/types/vue" {
  interface Vue {
    $moment: Moment,
    $Api:Api
  }
}

