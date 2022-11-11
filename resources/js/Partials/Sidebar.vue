<template>
  <div>
    <v-list-item class="" style="background-color:green;color:#FFF">
      <v-list-item-avatar height="30">
        <v-img src="/images/avatar.svg" width="30"></v-img>
      </v-list-item-avatar>

      <v-list-item-content>
        <v-list-item-title class="white--text">{{
          $page.props.user.nickname
        }}</v-list-item-title>
      </v-list-item-content>
    </v-list-item>

    <v-divider></v-divider>

    <v-list>
      <v-list-item
        href="/admin/monitoring/casual"
        link
        v-if="$can('view_monitoring')">
        
        <v-list-item-icon>
          <v-icon icon="user"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Monitoring
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/admin/payroll/groups"
        link
        v-if="$can('view_payroll_groups')">
        
        <v-list-item-icon>
          <v-icon icon="file"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Payroll Groups
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/payroll-officer/charging"
        link
        v-if="$can('view_charging')">
        
        <v-list-item-icon>
          <v-icon icon="calculator"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Charging
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/admin/payroll/casual/deduction"
        link
        v-if="$can('view_deduction')">
        
        <v-list-item-icon>
          <v-icon icon="dollar-sign"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Deduction Casual
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/signatory"
        link
        v-if="$can('view_signatory')">
        
        <v-list-item-icon>
          <v-icon icon="sign-out-alt"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Signatory
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/casual/withoutpay"
        link
        v-if="$can('view_withoutpay')">
        
        <v-list-item-icon>
          <v-icon icon="dollar-sign"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Casual Without Pay
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/casual/atm"
        link
        v-if="$can('view_atm')">
        
        <v-list-item-icon>
          <v-icon icon="lock"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Casual ATM
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <v-list-item
        href="/casual/export"
        link
        v-if="$can('view_debit')">
        
        <v-list-item-icon>
          <v-icon icon="dollar-sign"></v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>
              Casual Salary Debit
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

  <!-- <v-list-item
        v-for="item in items"
        :key="item.title"
        :href="item.link"
        link
      >
        <v-list-item-icon v-if="$can(item.gate)">
          <v-icon :icon="item.icon"></v-icon>
        </v-list-item-icon>

        <v-list-item-content v-if="$can(item.gate)">
          <v-list-item-title>
            {{ item.title }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item> -->
    </v-list>
  </div>
</template>

<script>
export default {
  data() {
    return {
      drawer: null,
      items: [
        {
          title: "Monitoring",
          icon: "user",
          link: "/admin/monitoring/casual",
          gate: "view_monitoring"
        },
        {
          title: "Payroll Groups",
          icon: "file",
          link: "/admin/payroll/groups",
          gate: "view_payroll_groups"
        },
        {
          title: "Charging",
          icon: "calculator",
          link: "/payroll-officer/charging",
          gate: "view_charging"
        },
        {
          title: "Deduction Casual",
          icon: "dollar-sign",
          link: "/admin/payroll/casual/deduction",
          gate: "view_deduction"
        },
        {
          title: "Signatory",
          icon: "sign-out-alt",
          link: "/signatory",
          gate: "view_signatory"
        },
        {
          title: "Casual Without Pay",
          icon: "dollar-sign",
          link: "/casual/withoutpay",
          gate: "view_withoutpay"
        },
        {
          title: "Casual ATM",
          icon: "lock",
          link: "/casual/atm",
          gate: "view_atm"
        },
        {
          title: "Casual Salary Debit",
          icon: "dollar-sign",
          link: "/casual/export",
          gate: "view_debit"
        },
      ],
    };
  },
};
</script>

<style>
</style>