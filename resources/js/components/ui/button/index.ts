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
        soft:
          "bg-indigo-50/80 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30",
        
        destructive:
          "bg-gradient-to-tr from-red-700 to-red-600 text-white hover:from-red-800 hover:to-red-700 focus-visible:ring-red-500/20",
        
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
