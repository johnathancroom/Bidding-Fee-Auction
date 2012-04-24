# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.rb definition is the authoritative source for your
# database schema. If you need to create the application database on another
# system, you should be using db:schema:load, not running all the migrations
# from scratch. The latter is a flawed and unsustainable approach (the more migrations
# you'll amass, the slower it'll run and the greater likelihood for issues).
#
# It's strongly recommended to check this file into your version control system.

ActiveRecord::Schema.define(:version => 20120421174859) do

  create_table "auctions", :force => true do |t|
    t.integer  "listing_id"
    t.integer  "price"
    t.integer  "user_id"
    t.integer  "end_time"
    t.datetime "created_at", :null => false
    t.datetime "updated_at", :null => false
  end

  add_index "auctions", ["id"], :name => "index_auctions_on_id", :unique => true

  create_table "listings", :force => true do |t|
    t.string   "name"
    t.integer  "retail"
    t.datetime "created_at", :null => false
    t.datetime "updated_at", :null => false
    t.string   "image_url"
  end

  add_index "listings", ["id"], :name => "index_listings_on_id", :unique => true

  create_table "users", :force => true do |t|
    t.string   "username"
    t.integer  "bids"
    t.datetime "created_at", :null => false
    t.datetime "updated_at", :null => false
  end

  add_index "users", ["id"], :name => "index_users_on_id", :unique => true

end
