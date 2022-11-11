<template>
	<app-layout>
		<div class="container mx-auto my-12">
			<div class="flex flex-row justify-center">
				<div class="w-full lg:w-2/3">
					<v-card>
						<v-card-header class="px-7 py-7">
							<p class="font-bold text-2xl">BECAME A VENDOR</p>
						</v-card-header>
						<v-card-body class="py-7 pb-10">
							<div class="flex flex-row">
								<div class="w-1/2 border-r px-8">
									<v-button block class="mx-1 my-1" size="lg" variant="primary">
										<v-icon :icon="['fab', 'facebook-square']"></v-icon>
										Facebook Login
									</v-button>
									<v-button block class="mx-1 my-1" size="lg" variant="danger">
										<v-icon :icon="['fab', 'google-plus-g']"></v-icon>
										Google+ Login</v-button
									>
									<v-button block size="lg" class="mx-1 my-1">
										<v-icon :icon="['fab', 'twitter']"></v-icon>
										Twitter Login
									</v-button>
								</div>
								<div class="w-1/2 px-8">
									<form @submit.prevent="submit">
										<v-input
											v-model="form.first_name"
											:error-text="form.errors.first_name"
											placeholder="First Name"
										></v-input>
										<v-input
											v-model="form.last_name"
											:error-text="form.errors.last_name"
											placeholder="Last Name"
										></v-input>
										<v-input
											v-model="form.email"
											:error-text="form.errors.email"
											type="email"
											placeholder="Email Address"
										></v-input>
										<v-input
											v-model="form.password"
											:error-text="form.errors.password"
											type="password"
											placeholder="Passowrd"
										></v-input>
										<v-input
											v-model="form.password_confirmation"
											:error-text="form.errors.password_confirmation"
											type="password"
											placeholder="Confirm Passowrd"
										></v-input>
										<v-button type="submit" block variant="warning"
											>Register</v-button
										>
									</form>
								</div>
							</div>
						</v-card-body>
					</v-card>
				</div>
			</div>
		</div>
	</app-layout>
</template>
<script>
	import VCard from "@/Components/Card";
	import VCardBody from "@/Components/CardBody";
	import VCardFooter from "@/Components/CardFooter";
	import VCardHeader from "@/Components/CardHeader";
	import VButton from "@/Components/Button";
	import VInput from "@/Components/Input";
	import AppLayout from "@/Partials/AppLayout";
	export default {
		components: {
			VCard,
			VCardBody,
			VCardFooter,
			VCardHeader,
			VButton,
			VInput,
			AppLayout,
		},
		data: function () {
			return {
				form: this.$inertia.form({
					first_name: "",
					last_name: "",
					email: "",
					password: "",
					password_confirmation: "",
					terms: false,
					role: [2, 3],
				}),
			};
		},
		methods: {
			submit() {
				this.form.post(this.route("register"), {
					onFinish: () => this.form.reset("password", "password_confirmation"),
				});
			},
		},
	};
</script>

<style>
</style>