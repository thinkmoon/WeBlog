import Vue from "vue";
import { Moment } from "moment";
import Api from "@/utils/fly/api";
declare module "vue/types/vue" {
  interface Vue {
    $moment: Moment,
    $api:Api
  }
}

