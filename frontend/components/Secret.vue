<template>
<v-container>
	<v-row justify="center" align="center">
    		<v-list-item>
                <v-list-item-content>Hash:</v-list-item-content>
                <v-list-item-content class="align-end">
                    {{ hash }}
                </v-list-item-content>
            </v-list-item>

            <v-list-item>
                <v-list-item-content>secretText:</v-list-item-content>
                <v-list-item-content class="align-end">
                    {{ secretText }}
                </v-list-item-content>
            </v-list-item>

            <v-list-item>
                <v-list-item-content>expiresAt:</v-list-item-content>
                <v-list-item-content class="align-end">
                    {{ expiresAt }}
                </v-list-item-content>
            </v-list-item>

            <v-list-item>
                <v-list-item-content>remainingViews:</v-list-item-content>
                <v-list-item-content class="align-end">
                    {{ remainingViews }}
                </v-list-item-content>
            </v-list-item>

            <v-list-item>
                <v-list-item-content>createdAt:</v-list-item-content>
                <v-list-item-content class="align-end">
                    {{ createdAt }}
                </v-list-item-content>
            </v-list-item>
        </v-row>
</v-container>
</template>

<script>
  export default {
  	props: ['hash', 'secretText', 'createdAt', 'expiresAt', 'remainingViews'],
    data: () => ({

    })
  }
</script>