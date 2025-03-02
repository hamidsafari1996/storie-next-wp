# 📖 Storie - Custom Post Type & REST API for WordPress & Next.js

🚀 **Storie** is a WordPress plugin that adds a **custom post type "Story"** and provides **a REST API** for seamless integration with **Next.js** projects. This plugin allows you to **manage stories and posts via API**, making it easy to fetch and display content in a modern **Next.js frontend**.

### ✨ Features 
✔️ Adds a **custom post type "Story"** to WordPress
✔️ Supports **title, content, and featured images**
✔️ Extends REST API with additional fields such as:
   - Gallery images
   - Author name
   - Author profile picture
   - Post categories
✔️ **Full REST API support** for easy integration with Next.js  
✔️ Manage stories and posts via API
✔️ Simple and customizable for developers

### 📌 Installation
#### 🔹 1. Download & Install the Plugin
1. Clone this repository or download the ZIP file and upload it to WordPress:
```bash
git clone https://github.com/hamidsafari1996/storie-plugin.git
```
2. Move the plugin folder to `wp-content/plugins/`.
3. In the WordPress dashboard, go to Plugins → Installed Plugins and activate Storie.

### 🔹 2. Check REST API
Once activated, you can access the custom REST API:
###### 📌 Get all stories:
`GET https://yourwebsite.com/wp-json/wp/v2/story`
###### 📌 Get a specific story:
`GET https://yourwebsite.com/wp-json/wp/v2/story/{id}`

### ⚙️ Connecting to Next.js
You can check your APIs in these sections:

`components/Main/box-shop.tsx`
```javascript
useEffect(() => {
            const fetchPosts = async () => {
                  try {
                        // Fetch posts from WordPress API
                        const response = await fetch('http://nextproject.local/wp-json/wp/v2/posts');
                        const data = await response.json();

                        // Transform API data into our Post interface format
                        const formattedPosts = data.map((post: any) => ({
                              id: post.id,
                              title: post.title.rendered,
                              excerpt: typeof post.excerpt === "object" ? post.excerpt.rendered : post.excerpt || '',
                              slug: post.slug,
                              subtitle: post.subtitle || '',
                              featured_image: post.featured_image_src || '',
                              logo: post.post_logo || '',
                              category: post.category_name || '',
                              author_avatar: post.author_avatar || '',
                              author_name: post.author_name || '',
                        }));
                        setPosts(formattedPosts);
                  } catch (error) {
                        console.error('Error fetching posts:', error);
                  }
            };

            fetchPosts();
      }, []);
```
`components/Main/Stories/storyService.ts`
```javascript
export const fetchStories = async (): Promise<Story[]> => {
  try {
    const response = await fetch('http://nextproject.local/wp-json/wp/v2/story');
    const data = await response.json();
    const formattedStories: Story[] = data.map((story: any) => ({
      id: story.id,
      title: story.title.rendered,
      images: Object.values(story.gallery_images || {}),
      logo: story.featured_image_src,
      date: story.date,
      viewed: false,
    }));
    return formattedStories;
  } catch (error) {
    console.error("Error fetching stories:", error);
    return [];
  }
};
```
###### This project is related to SocialPro, which you can download from this link.
[https://github.com/hamidsafari1996/SocialPro](https://github.com/hamidsafari1996/SocialPro "https://github.com/hamidsafari1996/SocialPro")