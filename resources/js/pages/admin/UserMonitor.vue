<script setup lang="ts">
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { router } from '@inertiajs/vue3'

interface User {
  id: number
  name: string
  email: string
  is_admin: boolean
  email_verified_at: string | null
  last_login_at: string | null
  last_login_ip: string | null
  last_login_user_agent: string | null
  last_login_location: string | null
  last_login_latitude: number | null
  last_login_longitude: number | null
  last_login_city: string | null
  last_login_country: string | null
  last_login_device_type: string | null
  created_at: string
  updated_at: string
}

interface UserStatistics {
  total_users: number
  admin_users: number
  regular_users: number
  active_users_last_week: number
  active_users_last_month: number
  new_users_this_month: number
  inactive_users: number
}

// Receive data as props from Inertia
const props = defineProps<{
  users: User[]
  statistics: UserStatistics
}>()

const error = ref('')

const formatDate = (dateString: string | null) => {
  if (!dateString) return 'Never'
  return new Date(dateString).toLocaleString()
}

const truncateUserAgent = (userAgent: string | null) => {
  if (!userAgent) return 'Unknown'
  return userAgent.length > 50 ? userAgent.substring(0, 50) + '...' : userAgent
}

const toggleAdminStatus = (user: User) => {
  router.patch(`/admin/users/${user.id}/toggle-admin`, {}, {
    onSuccess: () => {
      // Reload the page to get updated data
      router.reload()
    },
    onError: (errors) => {
      error.value = 'Failed to toggle admin status'
    }
  })
}
</script>

<template>
  <AppLayout>
    <div class="container mx-auto p-6 space-y-6">
      <div>
        <h1 class="text-3xl font-bold">User Monitor Dashboard</h1>
        <p class="text-muted-foreground">Monitor user activity, login tracking, and manage admin roles</p>
      </div>

      <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
        {{ error }}
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Total Users</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.total_users }}</div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Admin Users</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.admin_users }}</div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Active This Week</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.active_users_last_week }}</div>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">New This Month</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ statistics.new_users_this_month }}</div>
          </CardContent>
        </Card>
      </div>

      <!-- Users Table -->
      <Card>
        <CardHeader>
          <CardTitle>User Activity Monitor</CardTitle>
          <CardDescription>Track user logins, locations, and manage admin roles</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="overflow-x-auto">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>User</TableHead>
                  <TableHead>Role</TableHead>
                  <TableHead>Device</TableHead>
                  <TableHead>Last Login</TableHead>
                  <TableHead>Location</TableHead>
                  <TableHead>IP Address</TableHead>
                  <TableHead>Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="user in props.users" :key="user.id">
                  <TableCell>
                    <div>
                      <div class="font-medium">{{ user.name }}</div>
                      <div class="text-sm text-muted-foreground">{{ user.email }}</div>
                    </div>
                  </TableCell>
                  <TableCell>
                    <Badge :variant="user.is_admin ? 'destructive' : 'secondary'">
                      {{ user.is_admin ? 'Admin' : 'User' }}
                    </Badge>
                  </TableCell>
                  <TableCell>
                    <Badge v-if="user.last_login_device_type" variant="outline">
                      {{ user.last_login_device_type }}
                    </Badge>
                    <span v-else class="text-sm text-muted-foreground">Unknown</span>
                  </TableCell>
                  <TableCell>{{ formatDate(user.last_login_at) }}</TableCell>
                  <TableCell>
                    <div>
                      {{ user.last_login_location || 'Unknown' }}
                      <div v-if="user.last_login_latitude && user.last_login_longitude" class="text-xs text-muted-foreground">
                        GPS: {{ user.last_login_latitude?.toFixed(4) }}, {{ user.last_login_longitude?.toFixed(4) }}
                      </div>
                    </div>
                  </TableCell>
                  <TableCell>
                    <div>
                      {{ user.last_login_ip || 'N/A' }}
                      <div v-if="user.last_login_user_agent" class="text-xs text-muted-foreground" :title="user.last_login_user_agent">
                        {{ truncateUserAgent(user.last_login_user_agent) }}
                      </div>
                    </div>
                  </TableCell>
                  <TableCell>
                    <Button 
                      size="sm" 
                      :variant="user.is_admin ? 'outline' : 'default'"
                      @click="toggleAdminStatus(user)"
                    >
                      {{ user.is_admin ? 'Remove Admin' : 'Make Admin' }}
                    </Button>
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>