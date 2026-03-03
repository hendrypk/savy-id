import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Button } from "./Button.vue"

export const buttonVariants = cva(
  "w-full inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl text-sm font-medium transition-all disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg:not([class*='size-'])]:size-4 shrink-0 [&_svg]:shrink-0 outline-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive",
  {
    variants: {
      variant: {
        default:
          "bg-primary text-primary-foreground hover:bg-primary/90",
        
        // FRESH PURPLE: Gradasi Indigo yang vibrant tapi elegan
        purple:
          "bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/40 hover:from-indigo-700 hover:to-indigo-600 focus-visible:ring-indigo-500/30 border-t border-white/20",
        
        // FRESH EMERALD: Modern green for Income/Success actions
        emerald:
          "bg-gradient-to-tr from-emerald-600 to-emerald-500 text-white shadow-lg shadow-emerald-200 dark:shadow-emerald-900/40 hover:from-emerald-700 hover:to-emerald-600 focus-visible:ring-emerald-500/30 border-t border-white/20",
        
        soft:
          "bg-indigo-50/80 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30",
        
        // SOFT EMERALD: Subtle green background for secondary indicators
        soft_emerald:
          "bg-emerald-50/80 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-100/50 dark:border-emerald-900/30",

        destructive:
          "bg-gradient-to-tr from-rose-800 to-rose-900 text-slate-300 shadow-lg shadow-rose-200 dark:shadow-rose-900/30 hover:from-rose-700 hover:to-rose-600 focus-visible:ring-rose-500/30 border-t border-white/20",
        
        // SOFT ROSE: Versi sangat lembut untuk aksen pengeluaran
        soft_rose:
          "bg-rose-50/80 text-rose-600 hover:bg-rose-100 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-100/50 dark:border-rose-900/30",

        outline:
          "border bg-background shadow-xs hover:bg-slate-50 hover:text-accent-foreground dark:bg-slate-900/50 dark:border-slate-800 dark:hover:bg-slate-800",
        
        secondary:
          "bg-secondary text-secondary-foreground hover:bg-secondary/80",
        
        ghost:
          "hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950/30 dark:hover:text-indigo-400",
        
        ghost_red:
          "text-rose-500 hover:bg-rose-50 hover:text-rose-600 dark:hover:bg-rose-950/30 dark:text-rose-400",
        
        link: "text-indigo-600 dark:text-indigo-400 underline-offset-4 hover:underline",
      },
      size: {
        "default": "h-11 px-4 py-2 has-[>svg]:px-3",
        "sm": "h-10 rounded-md gap-1.5 px-3 has-[>svg]:px-2.5",
        "lg": "h-12 rounded-md px-6 has-[>svg]:px-4",
        "icon": "size-11",
        "icon-sm": "size-10",
        "icon-lg": "size-12",
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  },
)
export type ButtonVariants = VariantProps<typeof buttonVariants>
